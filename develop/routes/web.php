<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\UserDashboard;
use App\Livewire\AdminDashboard;

// Pagina de entrada — redirige al login o al dashboard según si está autenticado
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Distribuidor de roles — redirige según el rol del usuario
Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para usuarios normales
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-dashboard', UserDashboard::class)->name('user.dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas exclusivas para admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin-dashboard', function () {
        if (auth()->user()->rol !== 'admin') {
            return redirect()->route('user.dashboard');
        }
        return app(AdminDashboard::class)->render();
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';