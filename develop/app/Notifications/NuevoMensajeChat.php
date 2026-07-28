<?php

namespace App\Notifications;

use App\Models\Mensaje;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoMensajeChat extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Mensaje $mensaje)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'nuevo_mensaje',
            'ticket_id' => $this->mensaje->ticket_id,
            'de' => $this->mensaje->user->name,
            'mensaje' => Str::limit($this->mensaje->mensaje, 60),
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}