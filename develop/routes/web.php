<?php

use App\Http\Controllers\ProfileController;
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
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {

    // Usuario
    Route::get('/user-dashboard', UserDashboard::class)->name('user.dashboard');

    // Admin — protegido con middleware de rol
    Route::get('/admin-dashboard', AdminDashboard::class)
        ->middleware('es.admin')
        ->name('admin.dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';