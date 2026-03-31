<?php

namespace App\Exports;

use App\Models\Accounting\Payment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PaymentExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function collection()
    {
        return Payment::with(['supplier', 'account', 'creator'])->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'Payment Number',
            'Supplier',
            'Account',
            'Amount',
            'Remarks',
            'Outlet',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_number,
            $payment->supplier?->name ?? '-',
            $payment->account?->name ?? '-',
            $payment->amount ?? 0,
            $payment->remarks,
            $payment->outlet?->name ?? '-',
            Carbon::parse($payment->created_at)->format(app_date_time_format()),
            $payment->creator?->name ?? '-',
            Carbon::parse($payment->updated_at)->format(app_date_time_format()),
            $payment->editor?->name ?? '-',
        ];
    }
}
