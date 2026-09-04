<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TicketCambioEstadoPorUsuario extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        if (env('NATIVEPHP_ACTIVE', false)) {
            \Native\Desktop\Facades\Notification::title('Cambio en un ticket')
                ->message("{$this->ticket->user->name} {$accion} el ticket \"{$this->ticket->titulo}\"")
                ->show();
            
            return ['database', 'broadcast'];
        }
        return ['database', 'broadcast', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $accion = $this->ticket->estado === 'cancelado' ? 'canceló' : 'reabrió';

        return [
            'tipo' => 'cambio_estado_usuario',
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'usuario' => $this->ticket->user->name,
            'estado' => $this->ticket->estado,
            'mensaje' => "{$this->ticket->user->name} {$accion} el ticket \"{$this->ticket->titulo}\"",
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $accion = $this->ticket->estado === 'cancelado' ? 'canceló' : 'reabrió';

        return (new WebPushMessage)
            ->title('Cambio en un ticket')
            ->body("{$this->ticket->user->name} {$accion} el ticket \"{$this->ticket->titulo}\"")
            ->icon('/favicon.ico')
            ->data(['url' => '/admin/dashboard']);
    }
}