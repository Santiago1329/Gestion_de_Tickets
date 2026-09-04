<?php

namespace App\Notifications;

use App\Models\Mensaje;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NuevoMensajeChat extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Mensaje $mensaje)
    {
    }

    public function via(object $notifiable): array
    {
        if (env('NATIVEPHP_ACTIVE', false)) {
            \Native\Desktop\Facades\Notification::title("Nuevo mensaje de {$this->mensaje->user->name}")
                ->message(Str::limit($this->mensaje->mensaje, 80))
                ->show();
            
            return ['database', 'broadcast'];
        }
        return ['database', 'broadcast', WebPushChannel::class];
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

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $url = $notifiable->rol === 'admin' ? '/admin/dashboard' : '/user/dashboard';

        return (new WebPushMessage)
            ->title("Nuevo mensaje de {$this->mensaje->user->name}")
            ->body(Str::limit($this->mensaje->mensaje, 80))
            ->icon('/favicon.ico')
            ->data(['url' => $url]);
    }
}