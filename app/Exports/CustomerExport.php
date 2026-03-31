<?php

namespace App\Exports;

use App\Enums\TransactionType;
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
        $buckets = $this->getAgingBuckets($customer->ledgers);

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
            $customer->creator?->name ?? '-',
            Carbon::parse($customer->updated_at)->format(app_date_time_format()),
        ];
    }

    private function getAgingBuckets($ledgers): array
    {
        $buckets = [
            'current' => 0, // 0-30
            'days_30' => 0, // 31-60
            'days_60' => 0, // 61-90
            'days_90' => 0, // 90+
        ];

        // Step 1 — separate debits and total credits
        $debits = $ledgers
            ->filter(fn($e) => $e->amount > 0 && $e->transaction_type !== TransactionType::OPENING_BALANCE)
            ->sortBy('created_at')
            ->values();

        $totalCredits = $ledgers
            ->filter(fn($e) => $e->amount < 0)
            ->sum('amount');

        $remainingCredits = abs($totalCredits); // positive number to subtract

        // Step 2 — FIFO: knock off oldest invoices first
        foreach ($debits as $debit) {
            $outstanding = $debit->amount;

            if ($remainingCredits >= $outstanding) {
                // this invoice is fully paid
                $remainingCredits -= $outstanding;
                continue; // nothing left to bucket
            }

            // partially or not paid
            $outstanding -= $remainingCredits;
            $remainingCredits = 0;

            // Step 3 — bucket whatever is still outstanding
            $days = (int) Carbon::parse($debit->created_at)->diffInDays(now());

            if ($days <= 30)         $buckets['current'] += $outstanding;
            elseif ($days <= 60)     $buckets['days_30'] += $outstanding;
            elseif ($days <= 90)     $buckets['days_60'] += $outstanding;
            else                     $buckets['days_90'] += $outstanding;
        }

        return $buckets;
    }
}
