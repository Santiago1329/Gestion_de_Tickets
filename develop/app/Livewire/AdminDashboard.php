<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\Categoria;

class AdminDashboard extends Component
{
    use WithFileUploads;
    
    // Filtros
    public $filtroEstado = '';
    public $filtroCategoria = '';
    public $filtroPrioridad = '';

    // Modal crear Ticket
    public $titulo;
    public $descripcion;
    public $categoria_id;
    public $prioridad;
    public $archivo_adjunto;

    // Modal ver detalle
    public $ticketDetalle = null;

    // Modal editar
    public $ticketEditarId = null;
    public $editarEstado = "";
    public $editarPrioridad = "";

    // Abrir modal detalle
    public function verDetalle($id)
    {
        $this->ticketDetalle = Ticket::with(['categoria', 'user'])->findOrFail($id);
    }

    // Abrir modal editar
    public function abrirEditar($id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ticketEditarId = $id;
        $this->editarEstado = $ticket->estado;
        $this->editarPrioridad = $ticket->prioridad;
    }

    // Guardar cambios del modal editar
    public function guardarEdicion()
    {
        $this->validate([
            'editarEstado' => 'required|in:abierto,en_proceso,resuelto,re_abierto, cancelado',
            'editarPrioridad' => 'required|in:baja,media,alta',
        ]);

        Ticket::findOrFail($this->ticketEditarId)->update([
            'estado' => $this->editarEstado,
            'prioridad' => $this->editarPrioridad,
        ]);

        $this->reset(['ticketEditarId', 'editarEstado', 'editarPrioridad']);
        session()->flash('mensaje', 'El ticket ha sido actualizado');
        $this->dispatch('cerrarModalEditar');
    }

    // Crear ticket desde Admin
    public function guadarTicket()
    {
        // Validar datos del formulario
        $this->validate([
            'titulo' => 'required|max:150',
            'descripcion' => 'required',
            'archivo_adjunto' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridad' => 'required|in:baja,media,alta',
        ]);

        $rutaArchivo = null;

        // Si el usuario subio un archivo, lo guardamos en la carpeta 'archivos_adjuntos'
        if ($this->archivo_adjunto) {
            $rutaArchivo = $this->archivo_adjunto->store('archivos_adjuntos', 'public');
        }

        // Crear el nuevo ticket
        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'archivo_adjunto' => $rutaArchivo,
            'prioridad' => $this->prioridad,
            'categoria_id' => $this->categoria_id,
            'user_id' => auth()->id(),
            'estado' => 'abierto', // Estado inicial del ticket
        ]);

        $this->reset(['titulo', 'descripcion', 'categoria_id', 'archivo_adjunto', 'prioridad']);
        session()->flash('mensaje', 'Ticket creado exitosamente.');
        $this->dispatch('cerrarModalCrear');
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