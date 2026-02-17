<?php

namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages\EditPurchaseReturn;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages\ViewPurchaseReturn;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Schemas\PurchaseReturnForm;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Schemas\PurchaseReturnInfolist;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Tables\PurchaseReturnsTable;
use App\Models\Purchase\PurchaseReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseReturnResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = PurchaseReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUturnLeft;

    protected static ?string $recordTitleAttribute = 'return_number';

    public static function form(Schema $schema): Schema
    {
        return PurchaseReturnForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PurchaseReturnInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseReturnsTable::configure($table);
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
            'index' => ListPurchaseReturns::route('/'),
            'create' => CreatePurchaseReturn::route('/create'),
            'view' => ViewPurchaseReturn::route('/{record}'),
            'edit' => EditPurchaseReturn::route('/{record}/edit'),
        ];
    }
}
