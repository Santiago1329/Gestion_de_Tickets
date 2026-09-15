<?php

namespace App\Livewire;

use App\Models\Dispositivo;
use Livewire\Component;

class PanelDispositivos extends Component
{
    public function mount()
    {
    }

    public function render()
    {
        return view('components.panel-dispositivos', [
            'dispositivos' => Dispositivo::orderBy('sede')->orderBy('nombre')->get()
        ])->layout('layouts.app');
    }
}