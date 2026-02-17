<?php

namespace App\Support\Traits;

use App\Models\Inventory\InventoryLedger;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use App\Models\Sale\SaleReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sale\SaleReturnItem;

trait HasSalesWidgetFilters
{
    use DefaultPageFIlters;

    protected function getCustomerId(): ?int
    {
        return isset($this->pageFilters['customerId'])
            ? (int) $this->pageFilters['customerId']
            : null;
    }

    protected function applyFilters(Builder $query): Builder
    {
        if ($this->getOutletId()) {
            $query->where('outlet_id', $this->getOutletId());
        }

        if ($this->getStartDate() && $this->getEndDate()) {
            $query->whereBetween('created_at', [
                $this->getStartDate(),
                $this->getEndDate(),
            ]);
        }

        if ($this->getCustomerId()) {
            // Check if this is a SaleReturn query
            if ($query->getModel() instanceof SaleReturn) {
                $query->whereHas('sale', function ($q) {
                    $q->where('customer_id', $this->getCustomerId());
                });
            } else {
                $query->where('customer_id', $this->getCustomerId());
            }
        }

        return $query;
    }

    protected function applyLedgerFilters(Builder $query, ?array $classes = null): Builder
    {
        $classes = $classes ?? [SaleItem::class, SaleReturnItem::class];

        $query->whereIn('source_type', $classes);

        if ($this->getOutletId()) {
            $query->where('outlet_id', $this->getOutletId());
        }

        if ($this->getStartDate() && $this->getEndDate()) {
            $query->whereBetween('created_at', [
                $this->getStartDate(),
                $this->getEndDate(),
            ]);
        }

        if ($this->getCustomerId()) {
            $query->where(function ($query) use ($classes) {
                $hasSale = in_array(SaleItem::class, $classes);
                $hasReturn = in_array(SaleReturnItem::class, $classes);

                if ($hasSale) {
                    $query->whereHasMorph(
                        'source',
                        SaleItem::class,
                        fn($q) => $q->whereHas(
                            'sale',
                            fn($q) => $q->where('customer_id', $this->getCustomerId())
                        )
                    );
                }

                if ($hasReturn) {
                    $method = $hasSale ? 'orWhereHasMorph' : 'whereHasMorph';
                    $query->$method(
                        'source',
                        SaleReturnItem::class,
                        fn($q) => $q->whereHas(
                            'saleReturn',
                            fn($q) => $q->whereHas(
                                'sale',
                                fn($q) => $q->where('customer_id', $this->getCustomerId())
                            )
                        )
                    );
                }
            });
        }

        return $query;
    }

    protected function getFilteredSalesQuery(): Builder
    {
        return $this->applyFilters(Sale::query());
    }

    protected function getFilteredSalesReturnQuery(): Builder
    {
        return $this->applyFilters(SaleReturn::query());
    }

    protected function getFilteredInventoryQuery(): Builder
    {
        return $this->applyLedgerFilters(InventoryLedger::query());
    }

    protected function getFilteredInventoryQueryForSale(): Builder
    {
        return $this->applyLedgerFilters(InventoryLedger::query(), [SaleItem::class]);
    }

    protected function getFilteredInventoryQueryForSaleReturn(): Builder
    {
        return $this->applyLedgerFilters(InventoryLedger::query(), [SaleReturnItem::class]);
    }
}
