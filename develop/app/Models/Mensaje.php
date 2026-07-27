<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'mensaje',
        'ticket_id',
        'user_id',
        'leido_at',
    ];

    protected function casts(): array
    {
        return [
            'leido_at' => 'datetime',
        ];
    }

    // Un mensaje pertenece a un ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Un mensaje pertenece a un usuario (quien lo envió)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}