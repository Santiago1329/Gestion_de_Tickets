<?php

namespace App\Livewire;

use App\Models\Dispositivo;
use Livewire\Component;

class PanelDispositivos extends Component
{
    public ?string $sedeSeleccionada = null;

    // agregar Dispositivo
    public string $nombre = '';
    public string $ip = '';
    public string $estado = 'desconocido';

    // editar IP
    public ?int $dispositivoId = null;
    public string $ipEditar = '';

    public function seleccionarSede(string $sede): void
    {
        $this->sedeSeleccionada = $sede;
    }
    
    public function volverASedes(): void
    {
        $this->sedeSeleccionada = null;
    }

    public function abrirModalAgregar()
    {
        $this->reset(['nombre', 'ip', 'estado']);
        $this->resetValidation();

        $this->dispatch('abrirModalAgregarDispositivo');
    }

    public function abrirModalEditar(int $id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        $this->dispositivoId = $dispositivo->id;
        $this->ipEditar = $dispositivo->ip;

        $this->resetValidation();

        $this->dispatch('abrirModalEditarIp');
    }

    public function agregarDispositivo()
    {
        $this->validate([
            'nombre' => 'required|max:150',
            'ip' => 'required|ip',
            'estado' => 'required|in:online,offline,desconocido'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre excede el limite de caracteres',
            'ip.required' => 'La ip es obligatoria',
            'ip.ip' => 'La ip ingresada no es valida',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es valido',
        ]);

        Dispositivo::create([
            'nombre' => $this->nombre,
            'ip' => $this->ip,
            'sede' => $this->sedeSeleccionada,
            'estado' => $this->estado
        ]);

        $this->reset(['nombre', 'ip', 'estado']);
        $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Dispositivo agregado exitosamente');
        $this->dispatch('cerrarModalAgregar');
    }

    public function actualizarIp()
    {
        $this->validate([
            'ipEditar' => 'required|ip'
        ], [
            'ipEditar.required' => 'La ip es obligatoria',
            'ipEditar.ip' => 'La ip ingresada no es valida',
        ]);

        $dispositivo = Dispositivo::findOrFail($this->dispositivoId);
        $dispositivo->update([
            'ip' => $this->ipEditar
        ]);

        $this->reset(['dispositivoId', 'ipEditar']);
        $this->dispatch('mostrarToast', tipo: 'exito', mensaje: 'Ip modificada exitosamente');
        $this->dispatch('cerrarModalEditar');
    }

    public function render()
    {
        $sedes = Dispositivo::selectRaw('sede, COUNT(*) as total, SUM(estado = "online") as online, SUM(estado = "offline") as offline')
            ->groupBy('sede')
            ->get();

        $dispositivos = $this->sedeSeleccionada
            ? Dispositivo::where('sede', $this->sedeSeleccionada)->orderBy('nombre')->get()
            : collect();

        return view('components.panel-dispositivos', compact('sedes', 'dispositivos'))
            ->layout('layouts.app');
    }
}