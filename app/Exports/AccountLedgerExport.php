<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStrictNullComparison
};

use App\Models\Accounting\AccountLedger;

class AccountLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    protected float $runningBalance = 0;

    public function __construct(
        protected int $accountId,
        protected ?int $outletId = null,
    ) {}

    public function collection()
    {
        return AccountLedger::with([
            'account',
            'source',
            'outlet',
        ])
            ->where('account_id', $this->accountId)
            ->when($this->outletId, fn($q) => $q->where('outlet_id', $this->outletId))
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Account',
            'Debit',
            'Credit',
            'Balance',
            'Transaction Type',
            'Source',
            'Remarks',
            'Outlet',
            'Created',
            'Updated',
        ];
    }

    public function map($ledger): array
    {
        $debit  = $ledger->amount > 0 ? $ledger->amount : null;
        $credit = $ledger->amount < 0 ? abs($ledger->amount) : null;

        $this->runningBalance += $ledger->amount;

        return [
            $ledger->account?->name,
            $debit ?: 0,
            $credit ?: 0,
            $this->runningBalance,
            $ledger->transaction_type,
            $ledger->source && method_exists($ledger->source, 'resolveDocumentNumber')
                ? $ledger->source->resolveDocumentNumber()
                : '-',
            $ledger->remarks,
            $ledger->outlet?->name,
            Carbon::parse($ledger->created_at)->format(app_date_time_format()),
            Carbon::parse($ledger->updated_at)->format(app_date_time_format()),
        ];
    }
}
