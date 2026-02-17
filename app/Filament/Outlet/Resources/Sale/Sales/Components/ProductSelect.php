<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use Filament\Forms\Components\Select;

class ProductSelect
{
    public static function make()
    {
        return Select::make('product_id')
            ->relationship('product', 'name')
            ->disableOptionWhen(function ($value, $state, $get) {
                $selected = collect($get('../../items'))
                    ->pluck('product_id')
                    ->filter()
                    ->toArray();

                return in_array($value, $selected) && $state != $value;
            })
            ->afterStateUpdatedJs(<<<'JS'
                                const productId = $get('product_id');
                                const customerId = $get('../../customer_id');
                                const products = $get('../../products') ?? {};

                                let rate = 0;
                                let unit_id = null;

                                console.log(productId)
                                console.log(customerId)
                                console.log(products)

                                if (products[productId]) {

                                console.log(products[productId])

                                const customerRateObj = (products[productId].customer_rates || []).find(
                                    r => r.customer_id == customerId
                                );

                                if (customerRateObj) {
                                    rate = parseFloat(customerRateObj.selling_price);
                                } else {
                                    rate = parseFloat(products[productId].selling_price || 0);
                                }

                                unit_id = products[productId].unit_id ?? null;

                                console.log(unit_id, rate)

                                }

                                $set('rate', rate);
                                $set('unit_id', unit_id);

                                const qty = parseFloat($get('qty')) || 0;
                                $set('total', qty * rate);

                                const items = $get('../../items') ?? {};
                                const grandTotal = Object.values(items).reduce((sum, item) => {
                                    return sum + (parseFloat(item.total) || 0);
                                }, 0);

                                $set('../../grand_total', grandTotal);
                            JS)
            // ->live()
            // ->partiallyRenderComponentsAfterStateUpdated(['qty'])
            ->required();
    }
}
