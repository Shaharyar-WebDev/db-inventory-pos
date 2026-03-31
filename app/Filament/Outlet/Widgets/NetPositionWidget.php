<?php

namespace App\Filament\Outlet\Widgets;

use App\Services\NetPositionService;
use App\Support\Traits\DefaultPageFIlters;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NetPositionWidget extends StatsOverviewWidget
{
    use HasPageShield, InteractsWithPageFilters, DefaultPageFIlters;

    protected function getStats(): array
    {
        $data = NetPositionService::calculate(
            $this->getOutletId(),
        );

        return [
            Stat::make('Receivable', number_format($data['receivable'], 2))
                ->description('What customers owe you')
                ->color('info'),

            Stat::make('Stock Value', number_format($data['stock'], 2))
                ->description('Current inventory value')
                ->color('warning'),

            Stat::make('Accounts', number_format($data['accounts'], 2))
                ->description('Cash in bank / hand')
                ->color('success'),

            Stat::make('Liabilities', number_format($data['liabilities'], 2))
                ->description('What you owe suppliers')
                ->color('danger'),

            Stat::make('Net Position', number_format($data['net_position'], 2))
                ->description('Assets - Liabilities')
                ->color($data['net_position'] >= 0 ? 'success' : 'danger'),
        ];
    }
}
