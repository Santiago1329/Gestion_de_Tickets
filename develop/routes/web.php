<?php

use App\Models\Ticket;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Storage;
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

    // Panel de dispositivos
    Route::get('/admin/dispositivos', \App\Livewire\PanelDispositivos::class)
    ->middleware('es.admin')
    ->name('dispositivos.index');

    // Ruta que valida quien puede ver el archivo adjunto
    Route::get('/adjuntos/ticket/{ticket}', function (Ticket $ticket) {
        abort_unless(
            auth()->user()->rol === 'admin' || auth()->id() === $ticket->user_id,
            403
        );
        abort_unless($ticket->archivo_adjunto, 404);

        return Storage::disk('local')->response($ticket->archivo_adjunto);
    })->name('adjuntos.ticket');

    // Ruta que valida quien puede ver la imagen enviada en el chat de un ticket
    Route::get('/adjuntos/mensaje/{mensaje}', function (Mensaje $mensaje) {
        $ticket = $mensaje->ticket;
        abort_unless(
            auth()->user()->rol === 'admin' || auth()->id() === $ticket->user_id,
            403
        );
        abort_unless($mensaje->imagen, 404);

        return Storage::disk('local')->response($mensaje->imagen);
    })->name('adjuntos.mensaje');
});

// RUTA SOLO PARA DESARROLLO, PERMITE INICIAR SESIÓN COMO CUALQUIER USUARIO POR SU ID
if (app()->environment('local')) {
    Route::get('/dev-login/{userId}', function ($userId) {
        auth()->loginUsingId($userId);
        return redirect('/');
    });
}

require __DIR__.'/auth.php';
