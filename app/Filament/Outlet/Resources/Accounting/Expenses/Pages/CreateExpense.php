<?php

namespace App\Filament\Outlet\Resources\Accounting\Expenses\Pages;

use App\Filament\Outlet\Resources\Accounting\Expenses\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
