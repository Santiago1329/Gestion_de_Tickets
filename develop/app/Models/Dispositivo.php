<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $fillable = [
        'nombre',
        'ip',
        'sede',
        'estado',
        'ultimo_check_at'
    ];

    protected $casts = [
        'ultimo_check_at' => 'datetime',
    ];

    public function checks()
    {
        return $this->hasMany(DispositivoCheck::class);
    }
}
