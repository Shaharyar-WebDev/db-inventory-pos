<?php

namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseReturn extends ViewRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
