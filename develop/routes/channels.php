<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Broadcast;

/*
Canal del chat en vivo de un ticket

Solo pueden unirse:
    - El usuario dueño del ticket
    - Cualquier usuario con rol 'admin'
*/
Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = Ticket::find($ticketId);

    if (! $ticket) {
        return false;
    }

    return $user->rol === 'admin' || $user->id === $ticket->user_id;
});

/*
|--------------------------------------------------------------------------
| Canal privado personal de notificaciones
|--------------------------------------------------------------------------
| Cada usuario solo puede escuchar SU propio canal (Laravel lo usa por
| defecto para el broadcasting de notificaciones).
*/
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});