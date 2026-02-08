<?php

namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseReturns extends ListRecords
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
