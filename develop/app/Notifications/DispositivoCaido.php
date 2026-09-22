<?php

namespace App\Notifications;

use App\Models\Dispositivo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DispositivoCaido extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Dispositivo $dispositivo)
    {
    }

    public function via(object $notifiable): array
    {
        if (env('NATIVEPHP_ACTIVE', false)) {
            return ['database', 'broadcast'];
        }

        return ['database', 'broadcast', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'dispositivo_caido',
            'dispositivo_id' => $this->dispositivo->id,
            'mensaje' => "{$this->dispositivo->nombre} ({$this->dispositivo->ip}) está caído."
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        if (env('NATIVEPHP_ACTIVE', false)) {
            \Native\Desktop\Facades\Notification::title('Dispositivo caído')
                ->message("{$this->dispositivo->nombre} ({$this->dispositivo->ip}) no responde")
                ->show();
        }

        return $this->toArray($notifiable);
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Dispositivo caído')
            ->body("{$this->dispositivo->nombre} ({$this->dispositivo->ip}) no responde")
            ->icon('/favicon.ico')
            ->data(['url' => '/admin/dispositivos']);
    }
}
