<?php

namespace App\Exports;

use App\Models\TypeMembership;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class TypeMembershipsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithEvents
{
    public function collection()
    {
        return TypeMembership::orderBy('id', 'asc')->get();
    }

    public function map($typeMembership): array
    {
        return [
            $typeMembership->id,
            $typeMembership->name,
            $typeMembership->description,
            $typeMembership->price,
            $typeMembership->status == 1 ? 'Activo' : 'Inactivo',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio',
            'Estado',
        ];
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function styles(Worksheet $sheet)
    {
        // Título general
        $sheet->mergeCells('B3:F4'); // B-F (5 columnas)
        $sheet->setCellValue('B3', 'Lista de tipos de membresía');

        $sheet->getStyle('B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'rgb' => '4F81BD',
                ],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Encabezados
        $sheet->getStyle('B5:F5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'rgb' => '4F81BD',
                ],
            ],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Bordes y alineación
                $sheet->getStyle('B6:F' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Auto-ancho para cada columna
                foreach (range('B', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Colores de Estado
                for ($row = 6; $row <= $highestRow; $row++) {
                    $estado = $sheet->getCell('F' . $row)->getValue();
                    if (strtolower(trim($estado)) === 'inactivo') {
                        $sheet->getStyle('F' . $row)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000'],
                                'bold' => true,
                            ],
                        ]);
                    } elseif (strtolower(trim($estado)) === 'activo') {
                        $sheet->getStyle('F' . $row)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '00B050'],
                                'bold' => true,
                            ],
                        ]);
                    }
                }

                // Autofiltro
                $sheet->setAutoFilter('B5:F5');
            },
        ];
    }
}
