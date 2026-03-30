<?php

namespace App\Exports;

use App\Models\Accounting\Receipt;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function collection()
    {
        return Receipt::with(['customer', 'account', 'creator'])->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'Receipt Number',
            'Customer',
            'Account',
            'Amount',
            'Status',
            'Remarks',
            'Outlet',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($receipt): array
    {
        return [
            $receipt->receipt_number,
            $receipt->customer?->name ?? '-',
            $receipt->account?->name ?? '-',
            $receipt->amount ?? 0,
            $receipt->status?->label() ?? '-',
            $receipt->remarks,
            $receipt->outlet?->name ?? '-',
            $receipt->creator?->name ?? '-',
            Carbon::parse($receipt->created_at)->format(app_date_time_format()),
            Carbon::parse($receipt->updated_at)->format(app_date_time_format()),
        ];
    }
}
