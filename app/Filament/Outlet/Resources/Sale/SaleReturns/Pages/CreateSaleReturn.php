<?php

namespace App\Filament\Outlet\Resources\Sale\SaleReturns\Pages;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use App\Models\Sale\Sale;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    public function mount(): void
    {
        parent::mount();

        $saleId = request()->query('sale_id');
        $sale = Sale::with(
            'items',
            'saleReturns.items',
        )->find($saleId);

        if (!$sale) {
            abort(404, 'Sale not found');
        }

        $saleItemsTotal = $sale->items->sum('total');
        $saleTotal = $sale->total;
        $saleGrandTotal = $sale->grand_total;
        $saleDiscountType = $sale->discount_type;
        $saleDiscountValue = $sale->discount_value;
        $saleTaxCharges = $sale->tax_charges;
        $saleDeliveryCharges = $sale->delivery_charges;

        $this->form->fill([
            'sale' => $sale,
            'sale_id' => $sale->id,
            // 'total' => $saleTotal,
            'discount_type' => $saleDiscountType,
            // 'discount_value' => $saleDiscountValue,
            'discount_value' => 0,
            'delivery_charges' => $saleDeliveryCharges,
            'tax_charges' => $saleTaxCharges,
            // 'grand_total' => $saleGrandTotal,
        ]);
    }
}
