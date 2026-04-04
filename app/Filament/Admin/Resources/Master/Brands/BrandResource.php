<?php

namespace App\Filament\Admin\Resources\Master\Brands;

use App\Filament\Admin\Resources\Master\Brands\Pages\CreateBrand;
use App\Filament\Admin\Resources\Master\Brands\Pages\EditBrand;
use App\Filament\Admin\Resources\Master\Brands\Pages\ListBrands;
use App\Filament\Admin\Resources\Master\Brands\Pages\ViewBrand;
use App\Filament\Admin\Resources\Master\Brands\RelationManagers\ProductsRelationManager;
use App\Filament\Admin\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Admin\Resources\Master\Brands\Schemas\BrandInfolist;
use App\Filament\Admin\Resources\Master\Brands\Tables\BrandsTable;
use App\Models\Master\Brand;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BrandResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BrandInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'view' => ViewBrand::route('/{record}'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }


}
