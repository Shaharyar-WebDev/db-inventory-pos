<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Inventory\InventoryLedger;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventoryLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    protected float $runningBalance = 0;
    protected float $runningValuation = 0;

    public function __construct(
        protected int $productId,
        protected ?int $outletId = null,
    ) {}

    protected function canViewFinancials(): bool
    {
        return filament()->auth()->user()->can('ViewFinancials:Product');
    }

    public function collection()
    {
        return InventoryLedger::withCasts(default_ledger_casts())
            ->with([
                'product',
                'unit',
                'source',
            ])
            ->where('product_id', $this->productId)
            ->when($this->outletId, function ($q) {
                return $q->where('outlet_id', $this->outletId);
            })
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'Product',
            'Unit',
            'In',
            'Out',
            'Balance',
            'Rate',
        ];

        if ($this->canViewFinancials()) {
            $headings[] = 'Value';
            $headings[] = 'Valuation';
        }

        return array_merge($headings, [
            'Transaction Type',
            'Source',
            'Remarks',
            'Outlet',
            'Created',
            'Updated',
        ]);
    }

    public function map($ledger): array
    {
        $in  = $ledger->qty > 0 ? $ledger->qty : null;
        $out = $ledger->qty < 0 ? abs($ledger->qty) : null;

        $this->runningBalance += $ledger->qty;
        $this->runningValuation += $ledger->value;

        $row = [
            $ledger->product?->name,
            $ledger->unit?->name,
            $in ?: 0,
            $out ?: 0,
            $this->runningBalance,
            $ledger->rate,
        ];

        if ($this->canViewFinancials()) {
            $row[] = $ledger->value;
            $row[] = $this->runningValuation;
        }

        return array_merge($row, [
            $ledger->transaction_type->label(),
            $ledger->source && method_exists($ledger->source, 'resolveDocumentNumber')
                ? $ledger->source->resolveDocumentNumber()
                : '-',
            $ledger->remarks,
            $ledger->outlet->name,
            Carbon::parse($ledger->created_at)->format(app_date_time_format()),
            Carbon::parse($ledger->updated_at)->format(app_date_time_format()),
        ]);
    }
}
