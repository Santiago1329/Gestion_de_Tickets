<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificacionesDropdown extends Component
{
    public $authId;

    public function mount()
    {
        $this->authId = auth()->id();
    }

    // Escucha CUALQUIER notificación enviada al usuario autenticado
    #[On('echo-notification:App.Models.User.{authId}')]
    public function notificacionRecibida($notification): void
    {
        $this->dispatch('nueva-notificacion', mensaje: $notification['mensaje'] ?? '');
    }

    public function marcarLeida($id)
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('components.notificaciones-dropdown', [
            'notificaciones' => auth()->user()->notifications()->latest()->limit(15)->get(),
            'noLeidas' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}