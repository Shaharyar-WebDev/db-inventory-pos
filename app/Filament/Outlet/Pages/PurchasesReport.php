<?php

namespace App\Filament\Outlet\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class PurchasesReport extends Dashboard
{
    // protected string $view = 'filament.outlet.pages.purchases-report';

    protected static ?string $title = 'Outlet Purchase Report';

    protected static string $routePath = '/purchase-report';

    protected static string|BackedEnum|null $navigationIcon =  Heroicon::ChartBar;

    protected static bool $shouldRegisterNavigation = false;

    public function getWidgets(): array
    {
        return [
            // OutletSaleStats::class,
        ];
    }
}
