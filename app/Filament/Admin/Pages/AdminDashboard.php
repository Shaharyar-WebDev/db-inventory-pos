<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class AdminDashboard extends BaseDashboard
{
    use HasPageShield;

    // protected string $view = 'filament.admin.pages.dashboard';

    protected static ?string $title = 'Admin Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;

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
