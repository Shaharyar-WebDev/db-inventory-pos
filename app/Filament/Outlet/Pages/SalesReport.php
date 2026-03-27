<?php

namespace App\Filament\Outlet\Pages;

use App\Filament\Outlet\Navigation\ReportGroup;
use App\Filament\Outlet\Resources\Sale\Sales\Widgets\OutletSaleStats;
use App\Models\Master\Area;
use App\Models\Master\Brand;
use App\Models\Master\Category;
use App\Models\Master\City;
use App\Models\Master\Customer;
use App\Models\Master\Product;
use App\Models\Outlet\Outlet;
use App\Support\Actions\RefreshAction;
use BackedEnum;
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
use Filament\Support\Icons\Heroicon;

class SalesReport extends Dashboard
{
    use HasPageShield, HasFiltersForm, ReportGroup;

    // protected string $view = 'filament.outlet.pages.sales-report';

    protected static ?string $title = 'Outlet Sales Overview';

    protected static string $routePath = '/sales-overview';

    // protected bool $persistsFiltersInSession = true;

    protected static string|BackedEnum|null $navigationIcon =  Heroicon::ChartBarSquare;

    public function getWidgets(): array
    {
        return [
            OutletSaleStats::class,
            // TopProductsTable::class
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            RefreshAction::make(),
        ];
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
                                $set('customerId', null);
                                $set('areaId', null);
                                $set('cityId', null);
                                $set('categoryId', null);
                                $set('brandId', null);
                                $set('productId', null);
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
                        Select::make('customerId')
                            ->label('Customer')
                            ->options(Customer::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(0)
                            ->native(false)
                            ->dehydrated(false),
                        Select::make('areaId')
                            ->label('Area')
                            ->options(Area::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(0)
                            ->native(false)
                            ->dehydrated(false),
                        Select::make('cityId')
                            ->label('City')
                            ->options(City::options())
                            ->searchable()
                            ->preload(false)
                            ->optionsLimit(0)
                            ->native(false)
                            ->dehydrated(false),
                        Select::make('categoryId')
                            ->label('Category')
                            ->options(Category::options())
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('productId', null)),
                        Select::make('brandId')
                            ->label('Brand')
                            ->options(Brand::options())
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('productId', null)),
                        Select::make('productId')
                            ->label('Product')
                            ->options(
                                fn(Get $get) => Product::query()
                                    ->when($get('categoryId'), fn($q, $v) => $q->where('category_id', $v))
                                    ->when($get('brandId'), fn($q, $v) => $q->where('brand_id', $v))
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->native(false),
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
