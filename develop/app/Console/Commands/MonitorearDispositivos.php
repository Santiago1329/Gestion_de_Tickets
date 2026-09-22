<?php

namespace App\Console\Commands;

use App\Models\Dispositivo;
use App\Models\DispositivoCheck;
use App\Notifications\DispositivoCaido;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class MonitorearDispositivos extends Command
{
    protected $signature = 'dispositivos:monitorear';

    protected $description = 'Hace ping a todos los dispositivos que se encuentren registrados en la base de datos y notifica a los usuarios si alguno de ellos está offline.';

    public function handle(): void
    {
        $dispositivos = Dispositivo::all();

        foreach ($dispositivos as $dispositivo) {
            $estadoAnterior = $dispositivo->estado;
            $online = $this->ping($dispositivo->ip);
            $nuevoEstado = $online ? 'online' : 'offline';

            $dispositivo->update([
                'estado' => $nuevoEstado,
                'ultimo_check_at' => now(),
            ]);

            DispositivoCheck::create([
                'dispositivo_id' => $dispositivo->id,
                'estado' => $nuevoEstado,
                'checked_at' => now(),
            ]);

            // Envia notificación solo si el estado ha cambiado a offline
            if ($estadoAnterior !== 'offline' && $nuevoEstado === 'offline') {
                $admins = User::where('rol', 'admin')->get();
                if (env('NATIVEPHP_ACTIVE', false)) {
                    $admins->each->notifyNow(new DispositivoCaido($dispositivo));
                } else {
                    Notification::send($admins, new DispositivoCaido($dispositivo));
                }
            }

            $this->info("{$dispositivo->nombre} ({$dispositivo->ip}): {$nuevoEstado}");
        }
    }

    private function ping(string $ip): bool
    {
        $command = PHP_OS_FAMILY === 'Windows'
            ? "ping -n 1 -w 1000 {$ip}"
            : "ping -c 1 -W 1 {$ip}";

        exec($command, $output, $result);

        return $result === 0;
    }
}
