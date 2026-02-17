<?php

namespace App\Filament\Outlet\Widgets;

use App\Models\Inventory\InventoryLedger;
use App\Support\Traits\DefaultPageFIlters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class InventoryStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use DefaultPageFIlters;

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
        if ($this->getOutletId()) {
            $query->where('outlet_id', $this->getOutletId());
        }

        if ($this->getEndDate()) {
            $query->where(
                'created_at',
                '<=',
                $this->getEndDate()
            );
        }

        if ($this->getProductId()) {
            $query->where('product_id', $this->getProductId());
        }

        if ($this->getBrandId()) {
            $query->whereHas('product', function ($q) {
                $q->where('brand_id', $this->getBrandId());
            });
        }

        if ($this->getCategoryId()) {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', $this->getCategoryId());
            });
        }

        return $query;
    }

    public function getInventoryLedgerQuery(): Builder
    {
        return $this->applyFilters(InventoryLedger::query());
    }

    public function getInventoryValuation()
    {
        return $this->getInventoryLedgerQuery()->sum('value');
    }

    public function getAverageProductRate()
    {
        return $this->getInventoryLedgerQuery()->selectRaw('SUM(value) / NULLIF(SUM(qty), 0) as avg_rate')
            ->value('avg_rate') ?? 0;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Inventory Valuation', currency_format($this->getInventoryValuation())),

            Stat::make('Avg Rate', currency_format($this->getAverageProductRate())),
        ];
    }
}
