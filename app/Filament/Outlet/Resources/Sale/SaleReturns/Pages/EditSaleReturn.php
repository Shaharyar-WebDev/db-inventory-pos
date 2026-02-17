<?php

namespace App\Filament\Outlet\Resources\Sale\SaleReturns\Pages;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSaleReturn extends EditRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
