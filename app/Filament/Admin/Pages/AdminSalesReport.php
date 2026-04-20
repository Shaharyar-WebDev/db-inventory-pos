<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Outlet\Pages\SalesReport;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Support\Icons\Heroicon;

class AdminSalesReport extends SalesReport
{
    use HasPageShield;

    protected static ?string $title = 'Admin Sales Report';

    // protected string $view = 'filament.admin.pages.sales-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return false;
    // }
}
