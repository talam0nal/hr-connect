<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = Message::create([
            'theme'     => $request->theme,
            'message'   => nl2br($request->message),
            'user_id'   => \Auth::id(),
            'ticket_id' => $request->ticket_id,
        ]);
        return redirect()->route('tickets.show', $request->ticket_id)->with('success', __('general.message_success'));
    }

}