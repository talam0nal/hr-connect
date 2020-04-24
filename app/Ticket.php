<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    	'theme',
    	'message',
    	'file',
    	'status',
    	'is_closed',
    	'is_viewed',
    	'user_id',
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

}