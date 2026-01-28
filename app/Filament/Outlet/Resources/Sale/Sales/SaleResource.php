<?php

namespace App\Filament\Outlet\Resources\Sale\Sales;

use BackedEnum;
use App\Models\Sale\Sale;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\EditSale;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\ViewSale;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\ListSales;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\CreateSale;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use App\Filament\Outlet\Resources\Sale\Sales\Tables\SalesTable;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleInfolist;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sale_number';

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
            'index' => ListSales::route('/'),
            'create' => CreateSale::route('/create'),
            // 'view' => ViewSale::route('/{record}'),
            'edit' => EditSale::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
