<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases;

use App\Filament\Outlet\Resources\Purchase\Purchases\Pages\CreatePurchase;
use App\Filament\Outlet\Resources\Purchase\Purchases\Pages\EditPurchase;
use App\Filament\Outlet\Resources\Purchase\Purchases\Pages\ListPurchases;
use App\Filament\Outlet\Resources\Purchase\Purchases\Pages\ViewPurchase;
use App\Filament\Outlet\Resources\Purchase\Purchases\Schemas\PurchaseForm;
use App\Filament\Outlet\Resources\Purchase\Purchases\Schemas\PurchaseInfolist;
use App\Filament\Outlet\Resources\Purchase\Purchases\Tables\PurchasesTable;
use App\Models\Purchase\Purchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'purchase_number';

    public static function form(Schema $schema): Schema
    {
        return PurchaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PurchaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchasesTable::configure($table);
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
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            // 'view' => ViewPurchase::route('/{record}'),
            'edit' => EditPurchase::route('/{record}/edit'),
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
