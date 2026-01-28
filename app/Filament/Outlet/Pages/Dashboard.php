<?php

namespace App\Filament\Outlet\Pages;

use BackedEnum;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Dashboard extends BaseDashboard
{
    // protected string $view = 'filament.outlet.pages.dashboard';

    protected static ?string $title = null;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int|array
    {
        return 2;
    }

    protected int|string|array $columnSpan = 'full';

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            // FilamentInfoWidget::class,
        ];
    }
}
