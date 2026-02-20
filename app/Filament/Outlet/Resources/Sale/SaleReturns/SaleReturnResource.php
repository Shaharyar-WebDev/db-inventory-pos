<?php

namespace App\Filament\Outlet\Resources\Sale\SaleReturns;

use App\Filament\Outlet\Resources\Sale\SaleReturns\Pages\CreateSaleReturn;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Pages\EditSaleReturn;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Pages\ListSaleReturns;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Pages\ViewSaleReturn;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Schemas\SaleReturnForm;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Schemas\SaleReturnInfolist;
use App\Filament\Outlet\Resources\Sale\SaleReturns\Tables\SaleReturnsTable;
use App\Models\Sale\SaleReturn;
use App\Support\Traits\HasTimestampColumns;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SaleReturnResource extends Resource
{
    use HasTimestampColumns;

    protected static ?string $model = SaleReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUturnRight;

    protected static ?string $recordTitleAttribute = 'return_number';

    public static function form(Schema $schema): Schema
    {
        return SaleReturnForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaleReturnInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaleReturnsTable::configure($table);
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
            'index' => ListSaleReturns::route('/'),
            'create' => CreateSaleReturn::route('/create'),
            'view' => ViewSaleReturn::route('/{record}'),
            'edit' => EditSaleReturn::route('/{record}/edit'),
        ];
    }
}
