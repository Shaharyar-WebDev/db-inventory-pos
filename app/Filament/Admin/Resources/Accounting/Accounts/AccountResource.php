<?php

namespace App\Filament\Admin\Resources\Accounting\Accounts;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Accounting\Account;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;
use App\Filament\Admin\Resources\Accounting\Accounts\Pages\EditAccount;
use App\Filament\Admin\Resources\Accounting\Accounts\Pages\ViewAccount;
use App\Filament\Admin\Resources\Accounting\Accounts\Pages\ListAccounts;
use App\Filament\Admin\Resources\Accounting\Accounts\Pages\CreateAccount;
use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountForm;
use App\Filament\Admin\Resources\Accounting\Accounts\Tables\AccountsTable;
use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountInfolist;

class AccountResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = Account::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccountsTable::configure($table);
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
            'index' => ListAccounts::route('/'),
            // 'create' => CreateAccount::route('/create'),
            // 'view' => ViewAccount::route('/{record}'),
            // 'edit' => EditAccount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withBalances();
    }
}
