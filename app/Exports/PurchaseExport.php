<?php

namespace App\Exports;

use App\Models\Purchase\Purchase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function collection()
    {
        return Purchase::with(['supplier', 'creator'])->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'Purchase Number',
            'Supplier',
            'Grand Total',
            'Description',
            'Outlet',
            'Created At',
            'Created By',
            'Updated At',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->purchase_number,
            $purchase->supplier?->name ?? '-',
            $purchase->grand_total ?? 0,
            $purchase->description,
            $purchase->outlet?->name ?? '-',
            Carbon::parse($purchase->created_at)->format(app_date_time_format()),
            $purchase->creator?->name ?? '-',
            Carbon::parse($purchase->updated_at)->format(app_date_time_format()),
            $purchase->editor?->name ?? '-',
        ];
    }
}
