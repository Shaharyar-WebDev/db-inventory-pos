<?php

namespace App\Filament\Admin\Resources\Master\Products\Widgets;

use App\Filament\Outlet\Resources\Master\Products\Pages\ListProducts;
use App\Models\Inventory\InventoryLedger;
use App\Models\Master\Product;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static bool $isLazy = true;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }


    public ?string $pollingInterval = null;

    protected $listeners = ['refresh' => '$refresh'];

    protected function getProductCountStat(): Stat
    {
        return Stat::make('Total Products', fn() => Product::count())
            ->chartColor('primary')
            ->icon('heroicon-o-cube-transparent');
    }

    protected function getInventoryValuationStat(): Stat
    {
        return Stat::make(
            'Current Inventory Valuation',
            fn() =>
            currency_format(InventoryLedger::sum('value'))
        )
            ->icon('heroicon-o-currency-dollar');
    }

    protected function getAverageRateStat(): Stat
    {
        return Stat::make('Average Rate', function () {
            $avgRate = InventoryLedger::selectRaw(
                'SUM(value) / NULLIF(SUM(qty), 0) as avg_rate'
            )->value('avg_rate') ?? 0;

            return currency_format($avgRate);
        })
            ->chartColor('success')
            ->icon('heroicon-o-banknotes');
    }

    protected function getStats(): array
    {
        return [
            $this->getProductCountStat(),
            $this->getInventoryValuationStat(),
            $this->getAverageRateStat(),
        ];
    }
}
