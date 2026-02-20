<?php
namespace App\Filament\Admin\Resources\Accounting\Accounts;

use App\Filament\Admin\Resources\Accounting\Accounts\Pages\ListAccounts;
use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountForm;
use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountInfolist;
use App\Filament\Admin\Resources\Accounting\Accounts\Tables\AccountsTable;
use App\Models\Accounting\Account;
use App\Support\Traits\HasTimestampColumns;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AccountResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    use HasTimestampColumns;

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
