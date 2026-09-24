<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Notifications\DatabaseNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('dispositivos:monitorear')
    ->everyFiveMinutes()
    ->skip(fn () => config('nativephp-internal.running'));

Schedule::command('model:prune')->daily();

Schedule::call(function () {
    DatabaseNotification::whereNotNull('read_at')
        ->where('read_at', '<', now()->subDays(30))->delete();

    DatabaseNotification::whereNull('read_at')
        ->where('created_at', '<', now()->subDays(90))->delete();
})->daily()->name('limpiar-notificaciones');