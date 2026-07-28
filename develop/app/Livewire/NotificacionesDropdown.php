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
        $this->actualizarContadorTitulo();
    }

    #[On('echo-notification:App.Models.User.{authId}')]
    public function notificacionRecibida($notification): void
    {
        $this->dispatch('nueva-notificacion', mensaje: $notification['mensaje'] ?? '');
        $this->actualizarContadorTitulo();
    }

    public function marcarLeida($id)
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        $this->actualizarContadorTitulo();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->actualizarContadorTitulo();
    }

    private function actualizarContadorTitulo(): void
    {
        $this->dispatch('notif-contador-actualizado', total: auth()->user()->unreadNotifications()->count());
    }

    public function render()
    {
        return view('components.notificaciones-dropdown', [
            'notificaciones' => auth()->user()->notifications()->latest()->limit(15)->get(),
            'noLeidas' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}