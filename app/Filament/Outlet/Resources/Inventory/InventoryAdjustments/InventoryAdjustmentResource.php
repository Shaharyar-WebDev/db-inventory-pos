<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments;

use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\CreateInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\EditInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\ListInventoryAdjustments;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\ViewInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas\InventoryAdjustmentForm;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas\InventoryAdjustmentInfolist;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Tables\InventoryAdjustmentsTable;
use App\Models\Inventory\InventoryAdjustment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryAdjustmentResource extends Resource
{
    protected static ?string $model = InventoryAdjustment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'adjustment_number';

    public static function form(Schema $schema): Schema
    {
        return InventoryAdjustmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventoryAdjustmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryAdjustmentsTable::configure($table);
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
            'index' => ListInventoryAdjustments::route('/'),
            'create' => CreateInventoryAdjustment::route('/create'),
            // 'view' => ViewInventoryAdjustment::route('/{record}'),
            'edit' => EditInventoryAdjustment::route('/{record}/edit'),
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
