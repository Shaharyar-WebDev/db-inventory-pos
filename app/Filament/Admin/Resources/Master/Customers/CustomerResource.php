<?php

namespace App\Filament\Admin\Resources\Master\Customers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Customer;
use App\Support\Traits\HasTimestampColumns;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\Master\Customers\Pages\EditCustomer;
use App\Filament\Admin\Resources\Master\Customers\Pages\ViewCustomer;
use App\Filament\Admin\Resources\Master\Customers\Pages\ListCustomers;
use App\Filament\Admin\Resources\Master\Customers\Pages\CreateCustomer;
use App\Filament\Admin\Resources\Master\Customers\Schemas\CustomerForm;
use App\Filament\Admin\Resources\Master\Customers\Tables\CustomersTable;
use App\Filament\Admin\Resources\Master\Customers\Schemas\CustomerInfolist;
use App\Filament\Admin\Resources\Master\Customers\RelationManagers\ProductRatesRelationManager;

class CustomerResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    use HasTimestampColumns;

    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductRatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            // 'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCustomerBalances();
    }


}
