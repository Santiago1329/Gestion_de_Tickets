<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;

class DispositivoCheck extends Model
{
    use MassPrunable;

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

    public function prunable()
    {
        return static::where('checked_at', '<', now()->subDays(30));
    }
}
