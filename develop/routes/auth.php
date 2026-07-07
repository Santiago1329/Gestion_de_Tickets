<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MicrosoftController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::get('/login/microsoft', [MicrosoftController::class, 'redirect'])->name('microsoft.redirect');
    Route::get('/login/microsoft/callback', [MicrosoftController::class, 'callback'])->name('microsoft.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});