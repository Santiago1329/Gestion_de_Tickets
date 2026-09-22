<?php

namespace App\Notifications;

use App\Models\Dispositivo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DispositivoCaido extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Dispositivo $dispositivo)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', \App\Channels\TelegramChannel::class];
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

    public function toTelegram(object $notifiable): string
    {
        return $this->toArray($notifiable)['mensaje'];
    }
}
