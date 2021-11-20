<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/reset_password', 'App\Http\Controllers\AuthController@reset_password');
    Route::post('/reset_password/{token}', 'App\Http\Controllers\AuthController@confirmation_token');
});

Route::prefix('users')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\UserController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\UserController@show');
    Route::delete('/delete/{id}', 'App\Http\Controllers\UserController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\UserController@update');
    Route::get('/show_all/{id}', 'App\Http\Controllers\UserController@showUserCalendars'); 
    Route::get('/show_all/shared/{id}', 'App\Http\Controllers\UserController@showUserOnlyShared'); 
});

Route::prefix('calendars')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\CalendarsController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\CalendarsController@show');
    Route::post('/create', 'App\Http\Controllers\CalendarsController@store');
    Route::post('/delete/{id}', 'App\Http\Controllers\CalendarsController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\CalendarsController@update');
    Route::post('/share/{calendar_id}/{shareId}', 'App\Http\Controllers\CalendarsController@share');
});

Route::prefix('events')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\EventsController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\EventsController@show');
    Route::get('/show/calendar/{id}', 'App\Http\Controllers\EventsController@showByCalendar');
    Route::post('/create/{id}', 'App\Http\Controllers\EventsController@store');
    Route::delete('/delete/{calendar_id}/{event_id}', 'App\Http\Controllers\EventsController@destroy');
    Route::patch('/update/{event_id}', 'App\Http\Controllers\EventsController@updateById');
    Route::post('/show/date/{id}', 'App\Http\Controllers\EventsController@findEventsByDate');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
