<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketCambioEstadoPorUsuario extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', \App\Channels\TelegramChannel::class];
    }

    public function toDatabase(object $notifiable): array
    {
        if (env('NATIVEPHP_ACTIVE', false)) {
            \Native\Desktop\Facades\Notification::title('Cambio en un ticket')
                ->message("{$this->ticket->user->name} {$this->accion()} el ticket \"{$this->ticket->titulo}\"")
                ->show();
        }

        return $this->toArray($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'cambio_estado_usuario',
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'usuario' => $this->ticket->user->name,
            'estado' => $this->ticket->estado,
            'mensaje' => "{$this->ticket->user->name} {$this->accion()} el ticket \"{$this->ticket->titulo}\"",
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toTelegram(object $notifiable): string
    {
        return $this->toArray($notifiable)['mensaje'];
    }

    private function accion(): string
    {
        return $this->ticket->estado === 'cancelado' ? 'canceló' : 'reabrió';
    }
}