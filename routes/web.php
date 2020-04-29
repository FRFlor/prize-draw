<?php


use Illuminate\Support\Facades\Route;

// TODO: Allow registration of new administrative users via invitation email only
// For now registration and password reset from "Auth::routes()" should not be open
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::view('/', 'raffle')->name('raffle');
Route::resource('applicants', 'ApplicantsController');
Route::view('/join', 'join')->name('event.join');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('event.dashboard');
});

