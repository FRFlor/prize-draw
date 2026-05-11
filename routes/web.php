<?php


use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FileImportController;
use Illuminate\Support\Facades\Route;

// TODO: Allow registration of new administrative users via invitation email only
// For now registration and password reset from "Auth::routes()" should not be open
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::view('/', 'raffle')->name('raffle');
Route::view('/join', 'join')->name('event.join');
Route::resource('applicants', ApplicantsController::class);

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('event.dashboard');
    Route::post('upload/csv', [FileImportController::class, 'importFromCsv'])->name('import.csv');
});
