<?php

namespace App\Filament\Admin\Resources\Master\Suppliers;

use App\Filament\Admin\Resources\Master\Suppliers\Pages\CreateSupplier;
use App\Filament\Admin\Resources\Master\Suppliers\Pages\EditSupplier;
use App\Filament\Admin\Resources\Master\Suppliers\Pages\ListSuppliers;
use App\Filament\Admin\Resources\Master\Suppliers\Pages\ViewSupplier;
use App\Filament\Admin\Resources\Master\Suppliers\Schemas\SupplierForm;
use App\Filament\Admin\Resources\Master\Suppliers\Schemas\SupplierInfolist;
use App\Filament\Admin\Resources\Master\Suppliers\Tables\SuppliersTable;
use App\Models\Master\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;

class SupplierResource extends Resource
{
    use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SupplierForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplierInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliersTable::configure($table);
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
            'index' => ListSuppliers::route('/'),
            // 'create' => CreateSupplier::route('/create'),
            // 'view' => ViewSupplier::route('/{record}'),
            // 'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withBalances();
    }

}
