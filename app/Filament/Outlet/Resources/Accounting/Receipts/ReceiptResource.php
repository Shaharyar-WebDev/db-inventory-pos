<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Accounting\Receipt;
use Filament\Support\Icons\Heroicon;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\EditReceipt;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\ViewReceipt;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\ListReceipts;
use App\Filament\Outlet\Resources\Accounting\Receipts\Pages\CreateReceipt;
use App\Filament\Outlet\Resources\Accounting\Receipts\Schemas\ReceiptForm;
use App\Filament\Outlet\Resources\Accounting\Receipts\Tables\ReceiptsTable;
use App\Filament\Outlet\Resources\Accounting\Receipts\Schemas\ReceiptInfolist;

class ReceiptResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceipts::route('/'),
            'create' => CreateReceipt::route('/create'),
            // 'view' => ViewReceipt::route('/{record}'),
            'edit' => EditReceipt::route('/{record}/edit'),
        ];
    }
}
