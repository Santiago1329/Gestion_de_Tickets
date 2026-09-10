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
use Livewire\WithFileUploads;

class TicketChat extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $nuevoMensaje = '';

    public $imagen = null;

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

    public function quitarImagen(): void
    {
        $this->reset('imagen');
    }

    public function enviarMensaje(): void
    {
        $this->validate([
            'nuevoMensaje' => 'nullable|string|max:2000',
            'imagen' => 'nullable|image|max:5120', // 5MB
        ]);

        if (empty(trim($this->nuevoMensaje)) && !$this->imagen) {
            $this->addError('nuevoMensaje', 'Escribe un mensaje o adjunta una imagen.');
            return;
        }

        if ($this->ticket->estado === 'cancelado') {
            $this->dispatch('mostrarToast', tipo: 'error', mensaje: 'No puedes enviar mensajes en un ticket cancelado.');
            return;
        }

        $rutaImagen = null;
        if ($this->imagen) {
            $rutaImagen = $this->imagen->store('chat-imagenes', 'public');
        }

        $mensaje = Mensaje::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'mensaje' => trim($this->nuevoMensaje),
            'imagen' => $rutaImagen,
        ]);

        broadcast(new NuevoMensaje($mensaje))->toOthers();

        // Notificar al otro lado de la conversación
        if (auth()->user()->rol === 'admin') {
            if (env('NATIVEPHP_ACTIVE', false)) {
                $this->ticket->user->notifyNow(new NuevoMensajeChat($mensaje));
            } else {
                $this->ticket->user->notify(new NuevoMensajeChat($mensaje));
            }
        } else {
            $admins = User::where('rol', 'admin')->get();
            if (env('NATIVEPHP_ACTIVE', false)) {
                $admins->each->notifyNow(new NuevoMensajeChat($mensaje));
            } else {
                Notification::send($admins, new NuevoMensajeChat($mensaje));
            }
        }

        $this->reset('nuevoMensaje', 'imagen');
        $this->dispatch('chat-scroll-abajo');
    }

    public function render()
    {
        return view('components.ticket-chat', [
            'mensajes' => $this->ticket->mensajes()->with('user')->orderBy('created_at')->get(),
        ]);
    }
}