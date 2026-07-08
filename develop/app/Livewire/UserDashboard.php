<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Categoria;

class UserDashboard extends Component
{
    use WithFileUploads, WithPagination; // Traits

    // Propiedades para el formulario de creación de tickets
    public $titulo;
    public $descripcion;
    public $archivo_adjunto;
    public $categoria_id;
    public $tituloKey = 0;

    // Metodo para guardar un nuevo ticket
    public function guardarTicket()
    {
        // Validar datos del formulario
        $this->validate([
            'titulo' => 'required|max:150',
            'descripcion' => 'required',
            'archivo_adjunto' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 150 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'archivo_adjunto.file' => 'El archivo no es válido.',
            'archivo_adjunto.max' => 'El archivo no puede pesar más de 10MB.',
            'archivo_adjunto.mimes' => 'Solo se permiten imágenes y PDF.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
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
            'categoria_id' => $this->categoria_id,
            'user_id' => auth()->id(),
            'estado' => 'abierto', // Estado inicial del ticket
            'prioridad' => 'media', // Prioridad por defecto
        ]);

        // Limpiar el formulario después de guardar
        $this->reset(['titulo', 'descripcion', 'archivo_adjunto', 'categoria_id']);
        $this->tituloKey++;

        // Emitir un mensaje de exito
        $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Tu ticket ha sido enviado exitosamente');
    }

    // Metodo para cancelar un ticket, solo si el ticket esta en estado 'abierto' o 'en_proceso'
    public function cancelarTicket($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', auth()->id())->first();
        if ($ticket && in_array($ticket->estado, ['abierto', 'en_proceso'])) {
            $ticket->update(['estado' => 'cancelado']);
            $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Tu ticket ha sido cancelado exitosamente');
        }
    }

    // Metodo para reabrir un ticket, solo si el ticket esta en estado 'resuelto'
    public function reabrirTicket($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', auth()->id())->first();
        if ($ticket && $ticket->estado === 'resuelto') {
            $ticket->update(['estado' => 're_abierto']);
            $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Tu ticket ha sido reabierto exitosamente');
        }
    }

    // Metodo para renderizar la vista del dashboard del usuario
    public function render()
    {
        return view('components.user-dashboard', [
            'categorias' => Categoria::all(),
            'misTickets' => Ticket::where('user_id', auth()->id())
            ->with('categoria')
            ->latest()
            ->paginate(10)->withQueryString()
        ]);
    }
}