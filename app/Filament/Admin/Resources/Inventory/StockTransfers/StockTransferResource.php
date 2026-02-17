<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers;

use App\Filament\Admin\Resources\Inventory\StockTransfers\Pages\CreateStockTransfer;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Pages\EditStockTransfer;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Pages\ListStockTransfers;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Pages\ViewStockTransfer;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Schemas\StockTransferForm;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Schemas\StockTransferInfolist;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Tables\StockTransfersTable;
use App\Models\Inventory\StockTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTransferResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = StockTransfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsPointingIn;

    protected static ?string $recordTitleAttribute = 'transfer_number';

    public static function form(Schema $schema): Schema
    {
        return StockTransferForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTransferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransfersTable::configure($table);
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
            'index' => ListStockTransfers::route('/'),
            'create' => CreateStockTransfer::route('/create'),
            // 'view' => ViewStockTransfer::route('/{record}'),
            'edit' => EditStockTransfer::route('/{record}/edit'),
        ];
    }
}
