<?php

namespace App\Filament\Admin\Resources\Master\Products;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Product;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\Master\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\Master\Products\Pages\ViewProduct;
use App\Filament\Admin\Resources\Master\Products\Pages\ListProducts;
use App\Filament\Admin\Resources\Master\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\Master\Products\Schemas\ProductForm;
use App\Filament\Admin\Resources\Master\Products\Tables\ProductsTable;
use App\Filament\Admin\Resources\Master\Products\Schemas\ProductInfolist;

class ProductResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cube;

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
            'create' => CreateProduct::route('/create'),
            // 'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withStockCounts()->withStockValue()->withStockAvgRate();
    }
}
