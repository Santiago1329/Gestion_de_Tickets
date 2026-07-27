<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevoMensaje implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Mensaje $mensaje;

    public function __construct(Mensaje $mensaje)
    {
        $this->mensaje = $mensaje->load('user');
    }

    // Canal privado especifico de cada ticket.
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ticket.' . $this->mensaje->ticket_id),
        ];
    }

    // Nombre corto del evento que escucharemos en el front.
    public function broadcastAs(): string
    {
        return 'nuevo-mensaje';
    }

    // Datos que viajan al navegador.
    public function broadcastWith(): array
    {
        return [
            'id' => $this->mensaje->id,
            'mensaje' => $this->mensaje->mensaje,
            'ticket_id' => $this->mensaje->ticket_id,
            'user_id' => $this->mensaje->user_id,
            'user_name' => $this->mensaje->user->name,
            'created_at' => $this->mensaje->created_at->format('H:i'),
        ];
    }
}