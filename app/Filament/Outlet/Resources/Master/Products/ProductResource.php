<?php

namespace App\Filament\Outlet\Resources\Master\Products;

use App\Filament\Outlet\Resources\Master\Products\Pages\CreateProduct;
use App\Filament\Outlet\Resources\Master\Products\Pages\EditProduct;
use App\Filament\Outlet\Resources\Master\Products\Pages\ListProducts;
use App\Filament\Outlet\Resources\Master\Products\Pages\ViewProduct;
use App\Filament\Outlet\Resources\Master\Products\Schemas\ProductForm;
use App\Filament\Outlet\Resources\Master\Products\Schemas\ProductInfolist;
use App\Filament\Outlet\Resources\Master\Products\Tables\ProductsTable;
use App\Models\Master\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;

class ProductResource extends Resource
{
    use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = Product::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
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
            'index' => ListProducts::route('/'),
            // 'create' => CreateProduct::route('/create'),
            // 'view' => ViewProduct::route('/{record}'),
            // 'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withOutletStock();
    }


}
