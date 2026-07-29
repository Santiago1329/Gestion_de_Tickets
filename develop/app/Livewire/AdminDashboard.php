<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Categoria;
use App\Notifications\TicketEstadoActualizado;
use App\Exports\TicketsMensualExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboard extends Component
{
    use WithFileUploads, WithPagination;
    
    // Filtros
    public $filtroEstado = '';
    public $filtroCategoria = '';
    public $filtroPrioridad = '';

    // Reportes a excel
    public $reporteMes;
    public $reporteAnio;

    // Modal crear Ticket
    public $titulo;
    public $descripcion;
    public $categoria_id;
    public $prioridad;
    public $archivo_adjunto;
    public $telefono;
    public $tituloKey = 0;

    // Modal ver detalle
    public $ticketDetalle = null;

    // Modal editar
    public $ticketEditarId = null;
    public $editarEstado = "";
    public $editarPrioridad = "";
    public $estadosDisponibles = [];

    // Modal Chat
    public $ticketChat = null;

    // Abrir modal detalle
    public function verDetalle($id)
    {
        $this->ticketDetalle = Ticket::with(['categoria', 'user'])->findOrFail($id);
        $this->dispatch('abrirModalDetalle');
    }

    public function mount()
    {
        $this->reporteMes = now()->month;
        $this->reporteAnio = now()->year;
    }

    // Abrir modal de chat
    public function abrirChat($id)
    {
        $this->ticketChat = Ticket::findOrFail($id);
        $this->dispatch('abrirModalChat');
    }

    // Abrir modal editar
    public function abrirEditar($id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ticketEditarId = $id;
        $this->editarEstado = $ticket->estado;
        $this->editarPrioridad = $ticket->prioridad;
        $this->estadosDisponibles = $ticket->estadosDisponibles();
        $this->dispatch('abrirModalEditar');
    }

    public function abrirModalCrear()
    {
        $this->reset(['titulo', 'descripcion', 'categoria_id', 'archivo_adjunto', 'prioridad', 'telefono', 'ticketEditarId', 'editarEstado', 'editarPrioridad']);
        $this->dispatch('abrirModalCrearTicket');
    }

    // Guardar cambios del modal editar
    public function guardarEdicion()
    {
        $ticket = Ticket::findOrFail($this->ticketEditarId);
        $estadosValidos = $ticket->estadosDisponibles();

        $this->validate([
            'editarEstado' => 'required|in:' . implode(',', $estadosValidos),
            'editarPrioridad' => 'required|in:baja,media,alta',
        ]);

        $ticket->update([
            'estado' => $this->editarEstado,
            'prioridad' => $this->editarPrioridad,
        ]);

        $ticket->user->notify(new TicketEstadoActualizado($ticket));

        $this->reset(['ticketEditarId', 'editarEstado', 'editarPrioridad', 'estadosDisponibles']);
        $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'El ticket ha sido actualizado');
        $this->dispatch('cerrarModalEditar');
    }

    // Crear ticket desde Admin
    public function guardarTicket()
    {
        $this->validate([
            'titulo' => 'required|max:150',
            'descripcion' => 'required',
            'archivo_adjunto' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridad' => 'required|in:baja,media,alta',
            'telefono' => 'nullable|string|max:20',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 150 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'archivo_adjunto.file' => 'El archivo no es válido.',
            'archivo_adjunto.max' => 'El archivo no puede pesar más de 10MB.',
            'archivo_adjunto.mimes' => 'Solo se permiten imágenes y PDF.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'prioridad.required' => 'Debes seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
        ]);

        $rutaArchivo = null;
        if ($this->archivo_adjunto) {
            $rutaArchivo = $this->archivo_adjunto->store('archivos_adjuntos', 'public');
        }

        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'archivo_adjunto' => $rutaArchivo,
            'prioridad' => $this->prioridad,
            'categoria_id' => $this->categoria_id,
            'telefono' => $this->telefono,
            'user_id' => auth()->id(),
            'estado' => 'abierto',
        ]);

        $this->reset(['titulo', 'descripcion', 'categoria_id', 'archivo_adjunto', 'prioridad', 'telefono']);
        $this->tituloKey++;

        $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Ticket creado exitosamente.');
        $this->dispatch('cerrarModalCrear');
    }

    // Generar y descargar el reporte mensual en Excel
    public function generarReporte()
    {
        $this->validate([
            'reporteMes' => 'required|integer|between:1,12',
            'reporteAnio' => 'required|integer|min:2000|max:' . (now()->year + 1),
        ]);

        $this->dispatch('cerrarModalReporte');

        $nombreArchivo = "Reporte-tics-{$this->reporteAnio}-" . str_pad($this->reporteMes, 2, '0', STR_PAD_LEFT) . ".xlsx";

        return Excel::download(
            new TicketsMensualExport($this->reporteMes, $this->reporteAnio),
            $nombreArchivo
        );
    }

    // Metodos de reseteo
    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroCategoria()
    {
        $this->resetPage();
    }

    public function updatingFiltroPrioridad()
    {
        $this->resetPage();
    }

    // Metodo para renderizar la vista del dashboarad de Administrador
    public function render()
    {
        $ticket = Ticket::with(['categoria', 'user'])
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroCategoria, fn($q) => $q->where('categoria_id', $this->filtroCategoria))
            ->when($this->filtroPrioridad, fn($q) => $q->where('prioridad', $this->filtroPrioridad))
            ->latest()
            ->paginate(10)->withQueryString();
        
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