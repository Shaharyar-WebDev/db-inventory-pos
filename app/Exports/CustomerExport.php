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
        return Customer::with(['city', 'area', 'creator', 'ledgers'])
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
            'Aging (0-30 Days)',
            'Aging (31-60 Days)',
            'Aging (61-90 Days)',
            'Aging (90+ Days)',
            'Address',
            'Created At',
            'Created By',
            'Updated At',
        ];
    }

    public function map($customer): array
    {
        $buckets = $this->getAgingBuckets($customer->id);

        return [
            $customer->name,
            $customer->contact,
            $customer->city?->name ?? '-',
            $customer->area?->name ?? '-',
            $customer->opening_balance ?? 0,
            $customer->current_balance ?? 0,
            $buckets['current'],
            $buckets['days_30'],
            $buckets['days_60'],
            $buckets['days_90'],
            $customer->address,
            Carbon::parse($customer->created_at)->format(app_date_time_format()),
            $sale->creator?->name ?? '-',
            Carbon::parse($customer->updated_at)->format(app_date_time_format()),
        ];
    }

    private function getAgingBuckets($ledgers): array
    {
        $buckets = [
            'current' => 0,
            'days_30' => 0,
            'days_60' => 0,
            'days_90' => 0,
        ];

        foreach ($ledgers as $entry) {
            if ($entry->amount <= 0) continue;
            if ($entry->transaction_type === TransactionType::OPENING_BALANCE) continue;

            $days = (int) Carbon::parse($entry->created_at)->diffInDays(now());

            if ($days <= 30)          $buckets['current'] += $entry->amount;
            elseif ($days <= 60)      $buckets['days_30'] += $entry->amount;
            elseif ($days <= 90)      $buckets['days_60'] += $entry->amount;
            else                      $buckets['days_90'] += $entry->amount;
        }

        return $buckets;
    }
}
