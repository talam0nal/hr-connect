<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'theme',
        'message',
        'file',
        'user_id',
        'ticket_id',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function ticket()
    {
        return $this->hasOne('App\Ticket', 'id', 'ticket_id');
    }

}