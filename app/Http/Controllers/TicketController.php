<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{

    /**
     * Отображает список заявок
    */
    public function index()
    {
        $isManager = \Auth::user()->is_manager;
        if ($isManager) {
            $tickets = Ticket::get();
        } else {
            $tickets = Ticket::byCurrentUser()->get();
        }

        return view('index', compact('isManager', 'tickets'));
    }

    /**
     * Сохраняет новую заявку
    */
    public function store(Request $request)
    {
        if ($this->notAllowedByTime()) {
            return redirect()->route('tickets')->with('error', __('general.per_day'));
        }
        $ticket = Ticket::create([
            'theme'   => $request->theme,
            'message' => nl2br($request->message),
            'user_id' => \Auth::id(),
        ]);
        $this->saveFile($ticket);
        return redirect()->route('tickets')->with('success', __('general.success'));
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
        $lastTicket = Ticket::byCurrentUser()->latest()->first();
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
        return view('show', compact('ticket'));
    }

    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->is_closed = 1;
        $ticket->save();
        return response()->json(['message' => 'Ticket is closed']);
    }
}
