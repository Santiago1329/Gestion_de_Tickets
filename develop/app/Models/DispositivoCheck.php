<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositivoCheck extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'dispositivo_id',
        'estado',
        'checked_at'
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}
