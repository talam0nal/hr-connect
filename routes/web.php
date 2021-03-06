<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'TicketController@index')->name('main');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('tickets', 'TicketController');
	Route::resource('messages', 'MessageController');
	Route::get('/closeticket/{id}', 'TicketController@closeTicket')->name('ticket.close');
	Route::get('/applyTicket/{id}', 'TicketController@applyTicket')->name('ticket.apply');
});


