<?php

namespace App\Exports;

use App\Models\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function collection()
    {
        return Customer::with(['city', 'area', 'creator'])
            ->withCustomerBalances()
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Contact',
            'City',
            'Area',
            'Opening Balance',
            'Current Balance',
            'Address',
            'Created At',
            'Created By',
            'Updated At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->contact,
            $customer->city?->name ?? '-',
            $customer->area?->name ?? '-',
            $customer->opening_balance ?? 0,
            $customer->current_balance ?? 0,
            $customer->address,
            Carbon::parse($customer->created_at)->format(app_date_time_format()),
            $sale->creator?->name ?? '-',
            Carbon::parse($customer->updated_at)->format(app_date_time_format()),
        ];
    }
}
