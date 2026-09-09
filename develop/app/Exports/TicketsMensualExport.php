<?php

namespace App\Exports;

use App\Models\Ticket;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsMensualExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    protected int $mes;
    protected int $anio;

    public function __construct(
        string|Carbon $fechaInicio,
        string|Carbon $fechaFin
    ) {
        $this->fechaInicio = Carbon::parse($fechaInicio)->startOfDay();
        $this->fechaFin = Carbon::parse($fechaFin)->endOfDay();
    }

    // Trae solo los tickets creados en el mes/año indicado
    public function query()
    {
        return Ticket::query()
            ->with(['categoria', 'user'])
            ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
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
            'TIC-' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT),
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

    public function title(): string
    {
        if ($this->fechaInicio->format('Y-m') === $this->fechaFin->format('Y-m')
            && $this->fechaInicio->isStartOfMonth()
            && $this->fechaFin->isEndOfMonth()
        ) {
            $meses = [
                1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
            ];

            return $meses[$this->fechaInicio->month] . ' ' . $this->fechaInicio->year;
        }

        return $this->fechaInicio->format('d-m-Y') . ' al ' . $this->fechaFin->format('d-m-Y');
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // fila 1
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'], 
                ],
            ],
        ];
    }
}