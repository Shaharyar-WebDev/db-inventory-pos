<?php

namespace App\Exports;

use App\Models\Accounting\CustomerLedger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CustomerBalancesExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function collection()
    {
        return CustomerLedger::query()
            ->selectRaw('
            customer_id,
            SUM(amount) as balance
        ')
            ->groupBy('customer_id')
            ->with('customer')
            ->get();
    }


    public function headings(): array
    {
        return [
            'Customer',
            'Debit Balance',
            'Credit Balance',
        ];
    }

    public function map($row): array
    {
        $balance = (float) $row->balance;

        $debit = $balance > 0 ? $balance : 0;
        $credit  = $balance < 0 ? abs($balance) : 0;

        return [
            $row->customer?->name,
            $debit,
            $credit,
        ];
    }
}
