<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TelegramChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $mensaje = $notification->toTelegram($notifiable);

        Http::post("https://api.telegram.org/bot" . config('services.telegram.bot_token') . "/sendMessage", [
            'chat_id' => config(services.telegram.chat_id),
            'text' => $mensaje,
        ]);
    }
}