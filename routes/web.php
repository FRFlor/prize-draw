<?php


use Illuminate\Support\Facades\Auth;

// TODO: Allow registration of new administrative users via invitation email only
// For now registration and password reset from "Auth::routes()" should not be open
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/applicants', 'ApplicantsController@index');
Route::view('/', 'raffle');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'EventsController@dashboard')->name('event.dashboard');
});

