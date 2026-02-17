<?php

namespace App\Filament\Outlet\Resources\Accounting\Expenses;

use App\Filament\Outlet\Resources\Accounting\Expenses\Pages\CreateExpense;
use App\Filament\Outlet\Resources\Accounting\Expenses\Pages\EditExpense;
use App\Filament\Outlet\Resources\Accounting\Expenses\Pages\ListExpenses;
use App\Filament\Outlet\Resources\Accounting\Expenses\Pages\ViewExpense;
use App\Filament\Outlet\Resources\Accounting\Expenses\Schemas\ExpenseForm;
use App\Filament\Outlet\Resources\Accounting\Expenses\Schemas\ExpenseInfolist;
use App\Filament\Outlet\Resources\Accounting\Expenses\Tables\ExpensesTable;
use App\Models\Accounting\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?string $recordTitleAttribute = 'expense_number';

    public static function form(Schema $schema): Schema
    {
        return ExpenseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExpenseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
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
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            // 'view' => ViewExpense::route('/{record}'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}
