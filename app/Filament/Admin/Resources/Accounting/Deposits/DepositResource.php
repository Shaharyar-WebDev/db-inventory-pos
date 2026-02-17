<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits;

use App\Filament\Admin\Resources\Accounting\Deposits\Pages\CreateDeposit;
use App\Filament\Admin\Resources\Accounting\Deposits\Pages\EditDeposit;
use App\Filament\Admin\Resources\Accounting\Deposits\Pages\ListDeposits;
use App\Filament\Admin\Resources\Accounting\Deposits\Pages\ViewDeposit;
use App\Filament\Admin\Resources\Accounting\Deposits\Schemas\DepositForm;
use App\Filament\Admin\Resources\Accounting\Deposits\Schemas\DepositInfolist;
use App\Filament\Admin\Resources\Accounting\Deposits\Tables\DepositsTable;
use App\Models\Accounting\Deposit;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepositResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = Deposit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?string $recordTitleAttribute = 'deposit_number';

    public static function form(Schema $schema): Schema
    {
        return DepositForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepositInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepositsTable::configure($table);
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
            'index' => ListDeposits::route('/'),
            // 'create' => CreateDeposit::route('/create'),
            // 'view' => ViewDeposit::route('/{record}'),
            // 'edit' => EditDeposit::route('/{record}/edit'),
        ];
    }
}
