<?php

namespace App\Filament\Outlet\Pages;

use App\Filament\Outlet\Widgets\Stats;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class OutletDashboard extends BaseDashboard
{
    use HasPageShield;
    use HasFiltersForm;

    protected static ?string $title = 'Outlet Dashboard';

    protected static string|BackedEnum|null $navigationIcon =  Heroicon::Home;

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
