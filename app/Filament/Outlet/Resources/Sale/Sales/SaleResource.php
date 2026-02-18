<?php
namespace App\Filament\Outlet\Resources\Sale\Sales;

use App\Filament\Outlet\Resources\Sale\Sales\Pages\CreateSale;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\EditSale;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\ListSales;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\ViewSale;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleInfolist;
use App\Filament\Outlet\Resources\Sale\Sales\Tables\SalesTable;
use App\Models\Sale\Sale;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SaleResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = Sale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?string $recordTitleAttribute = 'sale_number';

    protected static null|SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Schema $schema): Schema
    {
        return SaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSales::route('/'),
            'create' => CreateSale::route('/create'),
            'view'   => ViewSale::route('/{record}'),
            'edit'   => EditSale::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
