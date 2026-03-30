<?php

namespace App\Filament\Admin\Resources\Accounting\ExpenseCategories\Pages;

use App\Filament\Admin\Resources\Accounting\ExpenseCategories\ExpenseCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenseCategories extends ListRecords
{
    protected static string $resource = ExpenseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
