<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserDashboard;
use App\Livewire\AdminDashboard;

// Pagina de entrada
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Distribuidor de roles
Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Usuario
    Route::get('/user-dashboard', UserDashboard::class)->name('user.dashboard');

    // Admin — protegido con middleware de rol
    Route::get('/admin-dashboard', AdminDashboard::class)
        ->middleware('es.admin')
        ->name('admin.dashboard');
});

require __DIR__.'/auth.php';
