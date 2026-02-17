<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Outlet\Pages\SalesReport as OutletSalesReport;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AdminSalesReport extends OutletSalesReport
{
    use HasPageShield;
    protected static ?string $title = 'Admin Sales Report';

    // protected string $view = 'filament.admin.pages.sales-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
