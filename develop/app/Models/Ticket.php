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
        'estado',
        'prioridad',
        'categoria_id',
        'user_id',
    ];

    // Relacion: Un ticket pertenece a una categoria
    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relacion: Un ticket pertenece a un usuario
    public function usuarios()
    {
        return $this->belongsTo(User::class);
    }
}
