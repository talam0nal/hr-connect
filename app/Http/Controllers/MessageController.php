<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reply;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $message = Message::create([
            'theme'     => $request->theme,
            'message'   => nl2br($request->message),
            'user_id'   => \Auth::id(),
            'ticket_id' => $request->ticket_id,
        ]);
        $ticket = Ticket::findOrFail($message->ticket_id);
        $text = $message->message;
        $ticket_id = $message->ticket_id;
        $isManager = \Auth::user()->is_manager;
        if ($isManager) {
            $this->notificateUserAboutReply($message, $ticket, $text, $ticket_id);
        }
        
        return redirect()->route('tickets.show', $request->ticket_id)->with('success', __('general.message_success'));
    }

    private function notificateUserAboutReply($message, $ticket, $text, $ticket_id)
    {
        Mail::to(\Auth::user()->email)->send(new Reply($message, $ticket, $text, $ticket_id));
    }

}