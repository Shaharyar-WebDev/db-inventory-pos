<?php

namespace App\Filament\Outlet\Pages;

use App\Filament\Outlet\Widgets\NetPositionWidget;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;

class OutletDashboard extends BaseDashboard
{
    use HasPageShield;
    // use HasFiltersForm;

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
                Section::make('Filters')
                    ->columnSpanFull()
                    ->collapsible()
                    ->persistCollapsed()
                    ->compact()
                    ->columns(4)
                    ->afterHeader([
                        Action::make('resetFilters')
                            ->link()
                            ->action(function (Set $set) {
                                $set('startDate', now());
                                $set('endDate', now());
                            }),
                    ])
                    ->schema([
                        DatePicker::make('startDate')
                            ->native(false)
                            ->maxDate(fn(Get $get, Set $set) => $get('endDate') ?: now())
                            ->default(now()),
                        DatePicker::make('endDate')
                            ->native(false)
                            ->minDate(fn(Get $get) => $get('startDate') ?: now())
                            ->maxDate(now())
                            ->default(now()),
                    ]),
            ]);
    }

    protected int|string|array $columnSpan = 'full';

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            // NetPositionWidget::class,
        ];
    }
}
