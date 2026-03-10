<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class CustomerExampleExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function array(): array
    {
        // empty data, just the template
        return [];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'City',
            'Area',
            'Contact',
            'Opening Balance',
        ];
    }
}
