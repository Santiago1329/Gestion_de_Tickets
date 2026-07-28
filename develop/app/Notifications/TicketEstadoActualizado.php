<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketEstadoActualizado extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    // Canales por los que se envía: se guarda en BD y se transmite en vivo por Reverb.
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    // Lo que se guarda en la columna `data` de la tabla notifications.
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'estado_actualizado',
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'estado' => $this->ticket->estado,
            'mensaje' => "Tu ticket TIC-" . str_pad($this->ticket->id, 4, '0', STR_PAD_LEFT) . " cambió a estado \"{$this->estadoLegible()}\"",
        ];
    }

    // Igual que toArray, pero es lo que viaja por Reverb en tiempo real
    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    private function estadoLegible(): string
    {
        return match ($this->ticket->estado) {
            'abierto' => 'Abierto',
            'en_proceso' => 'En proceso',
            'resuelto' => 'Resuelto',
            're_abierto' => 'Re-abierto',
            'cancelado' => 'Cancelado',
            default => $this->ticket->estado,
        };
    }
}