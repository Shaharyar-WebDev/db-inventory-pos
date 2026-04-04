<?php

namespace App\Filament\Admin\Pages;

use App\Exports\NetPositionExport;
use App\Filament\Admin\Widgets\NetPositionBreakdownWidget;
use App\Filament\Admin\Widgets\NetPositionStatsWidget;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use App\Support\Actions\RefreshAction;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;

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

    protected function getHeaderActions(): array
    {
        return [
            RefreshAction::make(),
            LedgerExportAction::configure(NetPositionExport::class)
                ->fileName(function () {
                    return "net_postion_" . now()->format(app_date_format());
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make('Export Net Position')
                ->color('success'),
        ];
    }

    protected int|string|array $columnSpan = 'full';

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            NetPositionStatsWidget::class,
            NetPositionBreakdownWidget::class
        ];
    }
}
