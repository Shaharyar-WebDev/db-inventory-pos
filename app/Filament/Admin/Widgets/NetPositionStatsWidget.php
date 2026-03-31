<?php

namespace App\Filament\Admin\Widgets;

use App\Services\NetPositionService;
use App\Support\Traits\DefaultPageFIlters;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Facades\Filament;
// use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NetPositionStatsWidget extends StatsOverviewWidget
{
    use HasPageShield,
        // InteractsWithPageFilters,
        DefaultPageFIlters;

    protected function getStats(): array
    {
        $data = NetPositionService::calculate();

        return [
            Stat::make('Receivable', 'Rs. ' . number_format($data['receivable'], 0))
                ->description('What customers owe — Global')
                ->color('info'),

            Stat::make('Stock Value', 'Rs. ' . number_format($data['total_stock'], 0))
                ->description('Total inventory across all outlets')
                ->color('warning'),

            Stat::make('Accounts', 'Rs. ' . number_format($data['accounts'], 0))
                ->description('Cash in bank / hand — Global')
                ->color('success'),

            Stat::make('Total Assets', 'Rs. ' . number_format($data['total_assets'], 0))
                ->description('Receivable + Stock + Accounts')
                ->color('success'),

            Stat::make('Supplier Payable', 'Rs. ' . number_format($data['liabilities'], 0))
                ->description('What you owe suppliers — Global')
                ->color('danger'),

            Stat::make('Total Expenses', 'Rs. ' . number_format($data['total_expenses'], 0))
                ->description('All outlet expenses combined')
                ->color('danger'),

            Stat::make('Net Position', 'Rs. ' . number_format($data['net_position'], 0))
                ->description('Assets - Liabilities')
                ->color($data['net_position'] >= 0 ? 'success' : 'danger'),
        ];
    }
}
