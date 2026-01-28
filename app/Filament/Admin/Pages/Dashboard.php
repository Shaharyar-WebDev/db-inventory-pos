<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // protected string $view = 'filament.admin.pages.dashboard';

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
