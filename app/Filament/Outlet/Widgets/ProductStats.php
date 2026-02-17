<?php

namespace App\Filament\Outlet\Widgets;

use App\Models\Inventory\InventoryLedger;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use App\Models\Sale\SaleReturnItem;
use App\Support\Traits\DefaultPageFIlters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class ProductStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters, DefaultPageFIlters;
    // use HasWidgetShield;

    protected static bool $isLazy = true;

    public ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 2;

    protected int | array | null $columns = 4;

    protected function getProductId(): ?int
    {
        return isset($this->pageFilters['productId'])
            ? (int) $this->pageFilters['productId']
            : null;
    }

    protected function getCategoryId(): ?int
    {
        return isset($this->pageFilters['categoryId'])
            ? (int) $this->pageFilters['categoryId']
            : null;
    }

    protected function getBrandId(): ?int
    {
        return isset($this->pageFilters['brandId'])
            ? (int) $this->pageFilters['brandId']
            : null;
    }

    protected function applyFilters(Builder $query): Builder
    {
        $query->join('sale_items', 'inventory_ledgers.source_id', '=', 'sale_items.id')
            ->where('inventory_ledgers.source_type', SaleItem::class);

        if ($this->getOutletId()) {
            $query->where('outlet_id', $this->getOutletId());
        }

        $query->when($this->getProductId(), function ($q) {
            $q->where('product_id', $this->getProductId());
        }, function ($q) {

            $q->whereHas('product', function ($product) {

                if ($this->getBrandId()) {
                    $product->where('brand_id', $this->getBrandId());
                }

                if ($this->getCategoryId()) {
                    $product->where('category_id', $this->getCategoryId());
                }
            });
        });

        return $query;
    }

    // public function getSalesQuery(): Builder
    // {
    //     // return $this->applyFilters(InventoryLedger::query());
    // }

    protected function getStats(): array
    {
        $totalCost = 0;
        $totalRate = 0;

        foreach (
            SaleItem::query()
                ->where(function ($query) {

                    if ($this->getProductId()) {
                        $query->orWhere('product_id', $this->getProductId());
                    }

                    if ($this->getBrandId()) {
                        $query->orWhereHas('product', function ($q) {
                            $q->where('brand_id', $this->getBrandId());
                        });
                    }

                    if ($this->getCategoryId()) {
                        $query->orWhereHas('product', function ($q) {
                            $q->where('category_id', $this->getCategoryId());
                        });
                    }
                })
                ->get() as $item
        ) {
            $qty = $item->product->toBaseQty($item->qty, $item->unit_id);
            $cost = $item->cost;
            $rate = $item->rate;

            $totalCost += $qty * $cost;
            $totalRate += $qty * $rate;
        };

        dd(
            InventoryLedger::query()
                ->join('sale_items', 'inventory_ledgers.source_id', '=', 'sale_items.id')
                ->whereIn('inventory_ledgers.source_type', [SaleItem::class, SaleReturnItem::class])
                ->when($this->getProductId(), function ($q) {
                    $q->where('product_id', $this->getProductId());
                }, function ($q) {

                    $q->whereHas('product', function ($product) {

                        if ($this->getBrandId()) {
                            $product->where('brand_id', $this->getBrandId());
                        }

                        if ($this->getCategoryId()) {
                            $product->where('category_id', $this->getCategoryId());
                        }
                    });
                })
                ->where('inventory_ledgers.outlet_id', 3)
                ->selectRaw('SUM(inventory_ledgers.qty * sale_items.rate) as total_value')
                ->selectRaw('SUM(inventory_ledgers.qty * inventory_ledgers.rate) as total_cost')
                ->first()
        );

        return [
            Stat::make('h', 1),
        ];
    }
}
