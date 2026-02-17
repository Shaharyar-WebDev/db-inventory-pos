<?php

namespace App\Filament\Outlet\Resources\Accounting\Expenses\Pages;

use App\Filament\Outlet\Resources\Accounting\Expenses\ExpenseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExpense extends ViewRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
