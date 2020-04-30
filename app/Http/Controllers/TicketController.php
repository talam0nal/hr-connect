<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isManager = \Auth::user()->is_manager;
        if ($isManager) {
            $tickets = Ticket::get();
        } else {
            $tickets = Ticket::where('user_id', \Auth::id())->get();
        }

        return view('index', compact('isManager', 'tickets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = Ticket::create([
            'theme'   => $request->theme,
            'message' => nl2br($request->message),
            'user_id' => \Auth::id(),
        ]);
        $this->saveFile($ticket);
        return redirect()->route('tickets')->with('success', 'Ваша заявка успешно добавлена');
    }

    private function saveFile(Ticket $ticket)
    {
        if (request()->hasFile('attachment')) {
            $path = request()->file('attachment')->store('public/files');
            $ticket->file = $path;
            $ticket->save();
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('show', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->is_closed = 1;
        $ticket->save();
        return response()->json(['message' => 'Ticket is closed']);
    }
}
