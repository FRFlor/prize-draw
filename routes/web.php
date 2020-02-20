<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/webstorm/register', 'PhpstormHandoutController@register');
Route::get('/webstorm/applicants', 'PhpstormHandoutController@index');
Route::view('/webstorm/raffle', 'raffle');
Route::view('/webstorm', 'enroll');
Route::redirect('/', '/webstorm/raffle');
