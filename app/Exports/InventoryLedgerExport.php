<?php

namespace App\Exports;

use App\Models\Inventory\InventoryLedger;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventoryLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    protected float $runningBalance = 0;

    public function __construct(
        protected int $productId,
        protected int $outletId,
    ) {}

    public function collection()
    {
        return InventoryLedger::with([
            'product',
            'unit',
            'source',
        ])
            ->where('product_id', $this->productId)
            ->where('outlet_id', $this->outletId)
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Product',
            'Unit',
            'In',
            'Out',
            'Balance',
            'Rate',
            'Value',
            'Transaction Type',
            'Reference',
            'Source',
            'Remarks',
        ];
    }

    public function map($ledger): array
    {
        $in  = $ledger->qty > 0 ? $ledger->qty : null;
        $out = $ledger->qty < 0 ? abs($ledger->qty) : null;

        $this->runningBalance += $ledger->qty;

        return [
            $ledger->created_at?->format('Y-m-d'),
            $ledger->product?->name,
            $ledger->unit?->name,
            $in ?: 0,
            $out ?: 0,
            $this->runningBalance,
            $ledger->rate,
            $ledger->value,
            $ledger->transaction_type,
            $ledger->reference?->{$ledger->reference::$documentNumberColumn},
            class_basename($ledger->source_type) . ' #' . $ledger->source_id,
            $ledger->remarks,
        ];
    }
}
