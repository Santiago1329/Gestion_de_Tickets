<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Campos de la tabla tickets
    protected $fillable = [
        'titulo',
        'descripcion',
        'archivo_adjunto',
        'telefono',
        'estado',
        'prioridad',
        'categoria_id',
        'user_id',
    ];

    // Metodo con los estados disponibles para los tickets
    public function estadosDisponibles(): array
    {
        return match($this->estado) {
            'abierto' => ['abierto', 'en_proceso', 'cancelado'],
            'en_proceso' => ['en_proceso', 'resuelto', 'cancelado'],
            'resuelto' => ['resuelto', 're_abierto'],
            're_abierto' => ['re_abierto', 'en_proceso', 'cancelado'],
            'cancelado' => ['cancelado'],
            default => ['abierto'],
        };
    }

    // Relacion: Un ticket pertenece a una categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relacion: Un ticket pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
