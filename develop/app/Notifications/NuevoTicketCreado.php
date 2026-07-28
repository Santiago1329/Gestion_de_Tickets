<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoTicketCreado extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'nuevo_ticket',
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'usuario' => $this->ticket->user->name,
            'prioridad' => $this->ticket->prioridad,
            'mensaje' => "{$this->ticket->user->name} creó un nuevo ticket: \"{$this->ticket->titulo}\"",
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}