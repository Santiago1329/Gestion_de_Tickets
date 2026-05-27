<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria; // Modelo de Categoria

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Computadores(Funcionamiento general - Mantenimiento)'],
            ['nombre' => 'Servidores - NAS - Switches (Dispositivos de red)'],
            ['nombre' => 'Disposyivos de reconocimiento facial (Control de acceso)'],
            ['nombre' => 'Impresoras - Escáneres'],
            ['nombre' => 'Grupos de tabajo - Carpetas ESPA'],
            ['nombre' => 'Aplicaciones - Programas (Local)'],
            ['nombre' => 'Correo electrónico - Servicios y aplicaciones (Web)'],
            ['nombre' => 'SAIMYR'],
            ['nombre' => 'Camaras - NVRs y DVRs - CEMEP'],
            ['nombre' => 'Antenas - SDWAN (Enlaces)'],
            ['nombre' => 'PLCs - Pantallas tactiles (Automatizaciones)'],
            ['nombre' => 'UPS - Conexion electrica'],
            ['nombre' => 'Documentación'],
            ['nombre' => 'Otros'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
