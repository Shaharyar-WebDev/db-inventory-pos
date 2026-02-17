<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Pages;

use App\Filament\Outlet\Resources\Purchase\Purchases\Action\CreatePurchaseReturnAction;
use App\Filament\Outlet\Resources\Purchase\Purchases\PurchaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchase extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CreatePurchaseReturnAction::make(),
        ];
    }
}
