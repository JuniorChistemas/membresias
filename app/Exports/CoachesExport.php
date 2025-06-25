<?php

namespace App\Exports;

use App\Models\Coach;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class CoachesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithEvents
{
    public function collection()
    {
        return Coach::orderBy('id', 'asc')->get();
    }

    public function map($coach): array
    {
        return [
            $coach->id,
            $coach->name,
            $coach->dni,
            $coach->specialty,
            $coach->phone,
            $coach->email,
            $coach->address,
            $coach->status == 1 ? 'Activo' : 'Inactivo',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'DNI',
            'Especialidad',
            'Teléfono',
            'Correo',
            'Dirección',
            'Estado',
        ];
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('B3:I4');
        $sheet->setCellValue('B3', 'Lista de entrenadores del gimnasio');

        $sheet->getStyle('B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Estilo para los encabezados
        $sheet->getStyle('B5:I5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Bordes y alineación para datos
                $sheet->getStyle('B6:I' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin'],
                    ],
                ]);

                // Auto-ancho
                foreach (range('B', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Estilo para "Estado"
                for ($row = 6; $row <= $highestRow; $row++) {
                    $estado = $sheet->getCell('I' . $row)->getValue();
                    if (strtolower(trim($estado)) === 'inactivo') {
                        $sheet->getStyle('I' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'FF0000'], 'bold' => true],
                        ]);
                    } elseif (strtolower(trim($estado)) === 'activo') {
                        $sheet->getStyle('I' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '00B050'], 'bold' => true],
                        ]);
                    }
                }

                // Autofiltro
                $sheet->setAutoFilter('B5:I5');
            },
        ];
    }
}
