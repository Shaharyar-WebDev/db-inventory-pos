<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Pages;

use App\Filament\Outlet\Resources\Purchase\Purchases\PurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void
    {
        $purchase = $this->record;
        $total = $purchase->items
            ->sum(fn($item) => ($item['qty'] ?? 0) * ($item['rate'] ?? 0));
        $purchase->grand_total = $total;
        $purchase->save();
    }
}
