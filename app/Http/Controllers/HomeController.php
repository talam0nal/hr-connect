<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $isManager = \Auth::user()->is_manager;
        if ($isManager) {
            $tickets = Ticket::get();
        } else {
            $tickets = Ticket::where('user_id', \Auth::id())->get();
        }

        return view('home', compact('isManager', 'tickets'));
    }
}