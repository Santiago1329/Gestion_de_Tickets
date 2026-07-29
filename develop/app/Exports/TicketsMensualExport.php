<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketsMensualExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected int $mes,
        protected int $anio
    ) {}

    // Trae solo los tickets creados en el mes/año indicado
    public function query()
    {
        return Ticket::query()
            ->with(['categoria', 'user'])
            ->whereYear('created_at', $this->anio)
            ->whereMonth('created_at', $this->mes)
            ->orderBy('created_at');
    }

    // Encabezados del Excel
    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Descripción',
            'Categoría',
            'Usuario',
            'Estado',
            'Prioridad',
            'Fecha de creación',
            'Última actualización',
        ];
    }

    // Convierte cada ticket en una fila
    public function map($ticket): array
    {
        return [
            $ticket->id,
            $ticket->titulo,
            $ticket->descripcion,
            $ticket->categoria->nombre ?? 'Sin categoría',
            $ticket->user->name ?? 'Usuario eliminado',
            ucfirst(str_replace('_', ' ', $ticket->estado)),
            ucfirst($ticket->prioridad),
            $ticket->created_at->format('d/m/Y H:i'),
            $ticket->updated_at->format('d/m/Y H:i'),
        ];
    }
}