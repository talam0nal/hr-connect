<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicket;
use App\Mail\CloseTicket;
use App\User;

class TicketController extends Controller
{

    /**
     * Отображает список заявок
    */
    public function index()
    {
        if (!\Auth::check()) {
            return redirect()->route('login');
        }

        $filter = request()->filter;

        $isManager = \Auth::user()->is_manager;
        if ($isManager) {
            $tickets = $filter ? $this->filterTickets($filter) : Ticket::get();
        } else {
            $tickets = Ticket::byCurrentUser()->get();
        }

        return view('index', compact('isManager', 'tickets'));
    }

    /**
     * Отфильтровавывает заявки по выбранному параметру
    */
    private function filterTickets($filter)
    {
        switch ($filter) {
            case 'viewed':
                return Ticket::onlyViewed()->get();
                break;
            case 'unviewed':
                return Ticket::onlyUnViewed()->get();
                break;
            case 'closed':
                return Ticket::onlyClosed()->get();
                break;
            case 'unclosed':
                return Ticket::onlyUnClosed()->get();
                break;
            case 'hasAnswer':
                return Ticket::whereHas('messages', function (Builder $query) {
                        $query->whereHas('user', function(Builder $q) {
                            $q->where('is_manager', 1);
                        });
                   })->get();
                break;
            case 'withoutAnswer':
                return Ticket::whereHas('messages', function (Builder $query) {
                        $query->whereHas('user', function(Builder $q) {
                            $q->where('is_manager', 0);
                        });
                   })->get();
                break;
        }
    }

    /**
     * Сохраняет новую заявку
    */
    public function store(Request $request)
    {
        if ($this->notAllowedByTime()) {
            return redirect()->route('main')->with('error', __('general.per_day'));
        }
        $ticket = Ticket::create([
            'theme'   => $request->theme,
            'message' => nl2br($request->message),
            'user_id' => \Auth::id(),
        ]);
        $this->saveFile($ticket);
        $this->newTicketNotification($ticket);
        return redirect()->route('main')->with('success', __('general.success'));

    }

    private function newTicketNotification($ticket)
    {
        $manager = User::isManager()->first();
        Mail::to($manager->email)->send(new NewTicket($ticket, \Auth::user()));
    }

    /**
     * Сохраняет файл
    */
    private function saveFile(Ticket $ticket)
    {
        if (request()->hasFile('attachment')) {
            $path = request()->file('attachment')->store('public/files');
            $ticket->file = $path;
            $ticket->save();
        }
    }

    /**
     * Если с момента подачи предыдущей заявки прошло менее суток,
     * возвращает true
    */
    private function notAllowedByTime()
    {
        return false; //Удалить на продакшене!
        $lastTicket = Ticket::byCurrentUser()->latest()->first();
        if (!$lastTicket) {
            return false;
        }
        $now = Carbon::now();
        $lt = Carbon::parse($lastTicket->created_at);
        if ($now->diffInSeconds($lt) <= 86400) {
            return true;
        }
    }

    /**
     * Отображает заявку
    */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        if (\Auth::user()->is_manager) {
            $ticket->is_viewed = 1;
            $ticket->save();
        }
        return view('show', compact('ticket'));
    }

    /**
     * Закрывает заявку
    */
    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->is_closed = 1;
        $ticket->save();
        $this->closeTicketNotification($ticket);
        return response()->json(['message' => 'Ticket is closed']);
    }

    private function closeTicketNotification(Ticket $ticket)
    {
        $manager = User::isManager()->first();
        Mail::to($manager->email)->send(new CloseTicket($ticket, \Auth::user()));        
    }

    /**
     * Меняет статус заявки на "Принято к выполнению"
    */
    public function applyTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 1;
        $ticket->save();
        return response()->json(['message' => 'Ticket is proccess']);
    }
}
