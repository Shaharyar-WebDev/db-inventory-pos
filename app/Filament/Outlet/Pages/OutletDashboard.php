<?php

namespace App\Filament\Outlet\Pages;

use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Widgets\AccountWidget;
use Filament\Forms\Components\Select;
use App\Filament\Outlet\Widgets\Stats;
use Filament\Schemas\Components\Section;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Utilities\Get;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class OutletDashboard extends BaseDashboard
{
    use HasPageShield;
    use HasFiltersForm;

    protected static ?string $title = 'Outlet Dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int|array
    {
        return 2;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        DatePicker::make('start_date')
                            ->maxDate(fn(Get $get) => $get('end_date') ?? now())
                            ->helperText(fn($state) => Carbon::parse($state)->format('d M Y'))
                            ->default(now()),
                        DatePicker::make('end_date')
                            ->minDate(fn(Get $get) => $get('start_date'))
                            ->helperText(fn($state) => Carbon::parse($state)->format('d M Y'))
                            ->maxDate(now())
                            ->default(now()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    protected int|string|array $columnSpan = 'full';

    public function getWidgets(): array
    {
        return [
            // AccountWidget::class,
            // FilamentInfoWidget::class,
            // Stats::class,
        ];
    }
}
