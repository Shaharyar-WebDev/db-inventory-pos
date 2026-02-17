<?php

namespace App\Filament\Outlet\Pages;

use App\Filament\Outlet\Navigation\ReportGroup;
use App\Filament\Outlet\Widgets\ProductStats;
use App\Models\Master\Brand;
use App\Models\Master\Category;
use App\Models\Master\Product;
use App\Models\Outlet\Outlet;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductSalesOverview extends Dashboard
{
    use ReportGroup, HasPageShield, HasFiltersForm;

    protected static ?string $title = 'Products Performance';

    protected static string $routePath = '/products-performance';

    public static bool $shouldRegisterNavigation = false;

    public function getWidgets(): array
    {
        return [
            ProductStats::class
        ];
    }

    public function getHeaderActions(): array
    {
        return [];
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
                    ->columns(6)
                    ->afterHeader([
                        Action::make('resetFilters')
                            ->link()
                            ->action(function (Set $set) {
                                $set('startDate', now());
                                $set('endDate', now());
                            }),
                    ])
                    ->schema([
                        Select::make('outletId')
                            ->label('Outlet')
                            ->options(Outlet::options())
                            ->searchable(false)
                            ->preload(false)
                            ->optionsLimit(0)
                            ->native(false)
                            ->afterStateHydrated(function (Set $set) {
                                $tenant = Filament::getTenant();
                                $set('outletId', $tenant?->id);
                            })
                            ->disabled(!is_null(Filament::getTenant()))
                            ->dehydrated(false),
                        Select::make('productId')
                            ->label('Product')
                            ->options(Product::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(10)
                            ->native(false)
                            ->dehydrated(false),
                        Select::make('brandId')
                            ->label('Brand')
                            ->options(Brand::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(10)
                            ->native(false)
                            ->dehydrated(false),
                        Select::make('categoryId')
                            ->label('Category')
                            ->options(Category::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(10)
                            ->native(false)
                            ->dehydrated(false),
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
}
