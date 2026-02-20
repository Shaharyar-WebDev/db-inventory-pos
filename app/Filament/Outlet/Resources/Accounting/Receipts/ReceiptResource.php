<?php
namespace App\Filament\Outlet\Resources\Accounting\Receipts;

use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\CreateReceipt;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\EditReceipt;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\ListReceipts;
use App\Filament\Outlet\Resources\Accounting\Receipts\Schemas\ReceiptForm;
use App\Filament\Outlet\Resources\Accounting\Receipts\Schemas\ReceiptInfolist;
use App\Filament\Outlet\Resources\Accounting\Receipts\Tables\ReceiptsTable;
use App\Filament\Outlet\Resources\Sale\Sales\RelationManagers\ReceiptSalesRelationManager;
use App\Models\Accounting\Receipt;
use App\Support\Traits\HasTimestampColumns;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReceiptResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    use HasTimestampColumns;

    protected static ?string $model = Receipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Document;

    protected static ?string $recordTitleAttribute = 'receipt_number';

    public static function form(Schema $schema): Schema
    {
        return ReceiptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReceiptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceiptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ReceiptSalesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReceipts::route('/'),
            'create' => CreateReceipt::route('/create'),
            // 'view' => ViewReceipt::route('/{record}'),
            'edit'   => EditReceipt::route('/{record}/edit'),
        ];
    }
}
