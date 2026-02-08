<?php

namespace App\Filament\Admin\Resources\Master\Products\Widgets;

use App\Models\Inventory\InventoryLedger;
use App\Models\Master\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends StatsOverviewWidget
{
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->chartColor('primary')
                ->icon('heroicon-o-cube-transparent'),

            Stat::make('Current Inventory Valuation', currency_format(InventoryLedger::sum('value')))
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Average rate', currency_format(InventoryLedger::selectRaw('SUM(value) / NULLIF(SUM(qty), 0) as avg_rate')->value('avg_rate') ?? 0))
                ->chartColor('success')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
