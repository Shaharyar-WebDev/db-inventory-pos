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

    public function collection()
    {
        return InventoryLedger::with([
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
        return [
            'Product',
            'Unit',
            'In',
            'Out',
            'Balance',
            'Rate',
            'Value',
            'Valuation',
            'Transaction Type',
            // 'Reference',
            'Source',
            'Remarks',
            'Outlet',
            'Created',
            'Updated'
        ];
    }

    public function map($ledger): array
    {
        $in  = $ledger->qty > 0 ? $ledger->qty : null;
        $out = $ledger->qty < 0 ? abs($ledger->qty) : null;

        $this->runningBalance += $ledger->qty;
        $this->runningValuation +=  $ledger->value;

        return [
            $ledger->product?->name,
            $ledger->unit?->name,
            $in ?: 0,
            $out ?: 0,
            $this->runningBalance,
            $ledger->rate,
            $ledger->value,
            $this->runningValuation,
            $ledger->transaction_type,
            // $ledger->reference?->{$ledger->reference::$documentNumberColumn},
            // class_basename($ledger->source_type) . ' #' . $ledger->source_id,
            $ledger->source && method_exists($ledger->source, 'resolveDocumentNumber')
                ? $ledger->source->resolveDocumentNumber()
                : '-',
            $ledger->remarks,
            $ledger->outlet->name,
            Carbon::parse($ledger->created_at)->format(app_date_time_format()),
            Carbon::parse($ledger->updated)->format(app_date_time_format()),
        ];
    }
}
