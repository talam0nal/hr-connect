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

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function scopeByCurrentUser($query)
    {
        return $query->where('user_id', \Auth::id());
    }

    public function scopeOnlyViewed($query)
    {
        return $query->where('is_viewed', 1);
    }

    public function scopeOnlyUnViewed($query)
    {
        return $query->where('is_viewed', 0);
    }

    public function scopeOnlyClosed($query)
    {
        return $query->where('is_closed', 1);
    }


    public function scopeOnlyUnClosed($query)
    {
        return $query->where('is_closed', 0);
    }

}