<?php

namespace App\Livewire;

use App\Events\NuevoMensaje;
use App\Models\Mensaje;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NuevoMensajeChat;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketChat extends Component
{
    public Ticket $ticket;

    public string $nuevoMensaje = '';

    public function mount(Ticket $ticket)
    {
        // Seguridad extra: si no es el dueño del ticket ni un admin, no puede entrar
        abort_unless(
            auth()->user()->rol === 'admin' || auth()->id() === $ticket->user_id,
            403
        );

        $this->ticket = $ticket;
    }

    // Escucha en tiempo real el canal privado del ticket.
    #[On('echo-private:ticket.{ticket.id},.nuevo-mensaje')]
    public function mensajeRecibido($event): void
    {
        $this->dispatch('chat-scroll-abajo');
    }

    public function enviarMensaje(): void
    {
        $this->validate([
            'nuevoMensaje' => 'required|string|max:2000',
        ]);

        if ($this->ticket->estado === 'cancelado') {
            $this->dispatch('mostrarToast', tipo: 'error', mensaje: 'No puedes enviar mensajes en un ticket cancelado.');
            return;
        }

        $mensaje = Mensaje::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'mensaje' => trim($this->nuevoMensaje),
        ]);

        broadcast(new NuevoMensaje($mensaje))->toOthers();

        // Notificar al otro lado de la conversación
        if (auth()->user()->rol === 'admin') {
            $this->ticket->user->notify(new NuevoMensajeChat($mensaje));
        } else {
            Notification::send(User::where('rol', 'admin')->get(), new NuevoMensajeChat($mensaje));
        }

        $this->reset('nuevoMensaje');
        $this->dispatch('chat-scroll-abajo');
    }

    public function render()
    {
        return view('components.ticket-chat', [
            'mensajes' => $this->ticket->mensajes()->with('user')->orderBy('created_at')->get(),
        ]);
    }
}