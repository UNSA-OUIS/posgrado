<?php

namespace App\Exports;

use App\Models\Uniforme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UniformesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Uniforme::all();
    }

    public function headings(): array
    {
        return [
            ["REPORTE TICKETS 2022"],
            [
                "Nro. Orden",
                "DNI",
                "Apellidos y nombres",
                "Tipo personal",
                "Área",            
                "Estado",
                "Entregado por"
            ]
        ];
    }
    public function map($ticket): array
    {
        return [
            $ticket->orden,
            $ticket->dni,
            $ticket->apn,
            $ticket->tipo_personal,
            $ticket->area,           
            $ticket->recogido ? "Entregado" : "Pendiente",
            $ticket->usuario != null ? $ticket->usuario->name : null,

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
