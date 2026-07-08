<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aseguramos que existan categorias y al menos un usuario
        if (Categoria::count() === 0) {
            $this->call(CategoriaSeeder::class);
        }

        $usuario = User::firstOrCreate(
            ['email' => 'usuario.prueba@example.com'],
            [
                'name' => 'Usuario de Prueba',
                'password' => bcrypt('password'),
                'rol' => 'usuario',
                'email_verified_at' => now(),
            ]
        );

        $categoriaIds = Categoria::pluck('id')->all();

        $tickets = [
            ['titulo' => 'No enciende el computador de recepción', 'estado' => 'abierto', 'prioridad' => 'alta', 'telefono' => '3001112233'],
            ['titulo' => 'Impresora no imprime a color', 'estado' => 'en_proceso', 'prioridad' => 'media', 'telefono' => '3002223344'],
            ['titulo' => 'Sin acceso a carpeta compartida ESPA', 'estado' => 'abierto', 'prioridad' => 'media', 'telefono' => null],
            ['titulo' => 'Cámara del pasillo principal desconectada', 'estado' => 'resuelto', 'prioridad' => 'alta', 'telefono' => '3003334455'],
            ['titulo' => 'Correo institucional no envía adjuntos', 'estado' => 'abierto', 'prioridad' => 'baja', 'telefono' => null],
            ['titulo' => 'Switch de la sala de sistemas reiniciando solo', 'estado' => 're_abierto', 'prioridad' => 'alta', 'telefono' => '3004445566'],
            ['titulo' => 'Control de acceso facial no reconoce huella', 'estado' => 'en_proceso', 'prioridad' => 'media', 'telefono' => null],
            ['titulo' => 'SAIMYR no carga reportes', 'estado' => 'abierto', 'prioridad' => 'alta', 'telefono' => '3005556677'],
            ['titulo' => 'Antena SDWAN con intermitencia', 'estado' => 'cancelado', 'prioridad' => 'baja', 'telefono' => null],
            ['titulo' => 'PLC de la línea 2 no responde', 'estado' => 'abierto', 'prioridad' => 'alta', 'telefono' => '3006667788'],
            ['titulo' => 'UPS de servidores suena alarma', 'estado' => 'en_proceso', 'prioridad' => 'alta', 'telefono' => '3007778899'],
            ['titulo' => 'Necesito instalar programa de nómina', 'estado' => 'resuelto', 'prioridad' => 'media', 'telefono' => null],
            ['titulo' => 'Escáner no detecta documentos', 'estado' => 'abierto', 'prioridad' => 'baja', 'telefono' => '3008889900'],
            ['titulo' => 'Solicitud de documentación de red interna', 'estado' => 'abierto', 'prioridad' => 'baja', 'telefono' => null],
            ['titulo' => 'NAS con espacio de almacenamiento lleno', 'estado' => 'en_proceso', 'prioridad' => 'media', 'telefono' => '3009990011'],
        ];

        foreach ($tickets as $data) {
            Ticket::create([
                'titulo' => $data['titulo'],
                'descripcion' => 'Descripción de prueba para el ticket: '.$data['titulo'],
                'archivo_adjunto' => null,
                'telefono' => $data['telefono'],
                'estado' => $data['estado'],
                'prioridad' => $data['prioridad'],
                'categoria_id' => $categoriaIds[array_rand($categoriaIds)],
                'user_id' => $usuario->id,
            ]);
        }
    }
}