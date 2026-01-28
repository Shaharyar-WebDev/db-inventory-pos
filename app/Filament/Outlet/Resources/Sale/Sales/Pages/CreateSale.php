<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Enums\DiscountType;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        $sale = $this->record;
        $total = $sale->items->sum('total');
        $grandTotal = $sale->items->sum('total');

        if ($sale->discount_type === DiscountType::PERCENT->value) {
            $grandTotal -= ($grandTotal * $sale->discount_value / 100);
        }

        if ($sale->discount_type === DiscountType::FIXED->value) {
            $grandTotal -= $sale->discount_value;
        }

        $sale->total = $total;
        $sale->grand_total = $grandTotal;
        $sale->save();
    }
}
