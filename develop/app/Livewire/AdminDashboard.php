<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Categoria;

class AdminDashboard extends Component
{
    public $filtroEstado = '';
    public $filtroCategoria = '';
    public $filtroPrioridad = '';

    // Metodo para cambiar estado a un ticket
    public function cambiarEstado($id, $nuevoEstado)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['estado' => $nuevoEstado]);
        session()->flash('mensaje', 'El estado del ticket ha sido actualizado');
    }

    // Metodo para cambiar prioridad a un ticket
    public function cambiarPrioridad($id, $nuevaPrioridad)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['prioridad' => $nuevaPrioridad]);
        session()->flash('mensaje', 'La prioridad del ticket ha sido actualizada');
    }

    // Metodo para renderizar la vista del dashboarad de Administrador
    public function render()
    {
        $ticket = Ticket::with(['categoria', 'user'])
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroCategoria, fn($q) => $q->where('categoria_id', $this->filtroCategoria))
            ->when($this->filtroPrioridad, fn($q) => $q->where('prioridad', $this->filtroPrioridad))
            ->get();
        
        return view('components.admin-dashboard', [
            'tickets' => $ticket,
            'categorias' => Categoria::all(),
            'totalTickets' => Ticket::count(),
            'totalAbiertos' => Ticket::whereIn('estado', ['abierto', 're_abierto'])->count(),
            'totalEnProceso' => Ticket::where('estado', 'en_proceso')->count(),
            'totalResueltos' => Ticket::where('estado', 'resuelto')->count(),
        ]);
    }
}