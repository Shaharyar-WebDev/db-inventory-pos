<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Inventory\InventoryAdjustment;
use App\Support\Traits\HasTimestampColumns;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\EditInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\ViewInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\ListInventoryAdjustments;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages\CreateInventoryAdjustment;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas\InventoryAdjustmentForm;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Tables\InventoryAdjustmentsTable;
use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas\InventoryAdjustmentInfolist;

class InventoryAdjustmentResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    use HasTimestampColumns;

    protected static ?string $model = InventoryAdjustment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsPointingIn;

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
}
