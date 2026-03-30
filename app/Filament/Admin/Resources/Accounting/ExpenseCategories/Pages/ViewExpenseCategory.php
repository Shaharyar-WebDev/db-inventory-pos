<?php

namespace App\Filament\Admin\Resources\Accounting\ExpenseCategories\Pages;

use App\Filament\Admin\Resources\Accounting\ExpenseCategories\ExpenseCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExpenseCategory extends ViewRecord
{
    protected static string $resource = ExpenseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
