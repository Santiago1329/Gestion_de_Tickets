<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Componentes Livewire
use App\Livewire\UserDashboard;
use App\Livewire\AdminDashboard;

// Pagina de entrada (Login / Registro)
Route::get('/', function () {
    // Si el usuario ya está autenticado, redirigir al dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Recibe los usuarios logueados y los desvia segun su rol
Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Si no es admin se asume que su rol es cliente (user)
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Ruta para el dashboard del usuario
    Route::get('/user-dashboard', UserDashboard::class)->name('user.dashboard');

    // Ruta para el dashboard del admin
    Route::get('/admin-dashboard', AdminDashboard::class)->name('admin.dashboard');
    
    // Rutas para la gestión del perfil del usuario (De Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
