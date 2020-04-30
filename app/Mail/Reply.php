<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reply extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $ticket;
    public $text;
    public $ticket_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $ticket, $text, $ticket_id)
    {
        $this->message = $message;
        $this->ticket = $ticket;
        $this->text = $text;
        $this->ticket_id = $ticket_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@hr-connect.ru')->subject($this->message->ticket->theme)->from('HRConnect')->view('mails.reply');
    }
}
