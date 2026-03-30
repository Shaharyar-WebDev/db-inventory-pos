<?php

namespace App\Filament\Admin\Resources\Accounting\ExpenseCategories\Pages;

use App\Filament\Admin\Resources\Accounting\ExpenseCategories\ExpenseCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseCategory extends CreateRecord
{
    protected static string $resource = ExpenseCategoryResource::class;
}
