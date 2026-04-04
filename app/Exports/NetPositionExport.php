<?php

namespace App\Exports;

use App\Services\NetPositionService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NetPositionExport implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Net Position';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
        ];
    }

    public function array(): array
    {
        $data = NetPositionService::calculate();

        return [
            ['Net Position', ''],
            ['', ''],
            ['Receivable',   number_format($data['receivable'], 2)],
            ['Stock',        number_format($data['total_stock'], 2)],
            ['Accounts',     number_format($data['accounts'], 2)],
            ['', ''],
            ['Total Assets', number_format($data['total_assets'], 2)],
            ['- Liabilities', number_format($data['liabilities'], 2)],
            ['', ''],
            ['Net Position', number_format($data['net_position'], 2)],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $netColor = NetPositionService::calculate()['net_position'] >= 0
            ? '16A34A'  // green
            : 'DC2626'; // red

        return [
            // Title
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],

            // Liabilities row (row 8)
            8 => [
                'font' => ['color' => ['rgb' => 'DC2626']],
            ],

            // Total Assets row (row 7)
            7 => [
                'font' => ['bold' => true],
                'borders' => [
                    'top' => ['borderStyle' => 'thin'],
                ],
            ],

            // Net Position row (row 10)
            10 => [
                'font' => [
                    'bold' => true,
                    'size' => 13,
                    'color' => ['rgb' => $netColor],
                ],
                'borders' => [
                    'top'    => ['borderStyle' => 'thin'],
                    'bottom' => ['borderStyle' => 'double'],
                ],
            ],
        ];
    }
}
