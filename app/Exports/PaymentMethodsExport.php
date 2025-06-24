<?php

namespace App\Exports;

use App\Models\PaymentMethod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentMethodsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithEvents
{
    public function collection()
    {
        return PaymentMethod::orderBy('id', 'asc')->get();
    }

    public function map($paymentMethod): array
    {
        return [
            $paymentMethod->id,
            $paymentMethod->name,
            $paymentMethod->description,
            $paymentMethod->status == 1 ? 'Activo' : 'Inactivo',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
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
        $sheet->mergeCells('B3:E4');
        $sheet->setCellValue('B3', 'Lista de métodos de pago');

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

        // Estilo de encabezado
        $sheet->getStyle('B5:E5')->applyFromArray([
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
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Bordes y alineación
                $sheet->getStyle('B6:E' . $highestRow)->applyFromArray([
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

                // Autoancho
                foreach (range('B', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Colores para "Estado"
                for ($row = 6; $row <= $highestRow; $row++) {
                    $estado = $sheet->getCell('E' . $row)->getValue();
                    if (strtolower(trim($estado)) === 'inactivo') {
                        $sheet->getStyle('E' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'FF0000'], 'bold' => true],
                        ]);
                    } elseif (strtolower(trim($estado)) === 'activo') {
                        $sheet->getStyle('E' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '00B050'], 'bold' => true],
                        ]);
                    }
                }

                // Autofiltro
                $sheet->setAutoFilter('B5:E5');
            },
        ];
    }
}
