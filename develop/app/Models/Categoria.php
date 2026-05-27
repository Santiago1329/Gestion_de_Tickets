<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Campos de la tabla categoria
    protected $fillable = ['nombre'];

    // Relacion: Una categoria tiene muchos tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
