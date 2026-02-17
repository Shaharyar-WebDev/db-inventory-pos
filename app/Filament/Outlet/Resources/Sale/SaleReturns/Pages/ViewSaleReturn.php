<?php

namespace App\Filament\Outlet\Resources\Sale\SaleReturns\Pages;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSaleReturn extends ViewRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
