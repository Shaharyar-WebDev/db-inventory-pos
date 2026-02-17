<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;

class UnitSelect
{
    public static function make($productsKeyedArray)
    {
        return Select::make('unit_id')
            ->relationship('unit', 'name')
            ->disableOptionWhen(function ($value, $state, $get) use ($productsKeyedArray) {
                $productId = $get('product_id');
                $product = $productsKeyedArray[$productId] ?? null;

                $unitId = $product['unit_id'] ?? null;
                $subUnitId = $product['sub_unit_id'] ?? null;

                return !in_array($value, [$unitId, $subUnitId]);
            })
            ->rules(function (Get $get) use ($productsKeyedArray) {
                $productId = $get('product_id');
                $product   = $productsKeyedArray[$productId] ?? null;

                if (! $product) {
                    return [];
                }

                $allowedUnits = array_filter([
                    $product['unit_id'] ?? null,
                    $product['sub_unit_id'] ?? null,
                ]);

                if (empty($allowedUnits)) {
                    return [];
                }

                return [
                    'required',
                    'in:' . implode(',', $allowedUnits),
                ];
            })
            ->afterStateUpdatedJs(<<<'JS'
                                const productId = $get('product_id');
                                const selectedUnitId = $get('unit_id');
                                const customerId = $get('../../customer_id');
                                const products = $get('../../products') ?? {};

                                let rate = 0;
                                let baseRate = 0;

                                if (products[productId]) {
                                    const product = products[productId];

                                    const customerRateObj = (product.customer_rates || []).find(
                                        r => r.customer_id == customerId
                                    );

                                    rate = parseFloat(
                                        customerRateObj?.selling_price ??
                                        product.selling_price ??
                                        0
                                    );

                                    const productUnitId = product.unit_id;
                                    const productSubUnitId = product.sub_unit_id;
                                    const conversion = parseFloat(product.sub_unit_conversion ?? 0);

                                    console.log(productUnitId);
                                    console.log(productSubUnitId);
                                    console.log(conversion);
                                    console.log(selectedUnitId);



                                    if (selectedUnitId == productUnitId) {
                                        baseRate = rate;
                                    } else if (selectedUnitId == productSubUnitId) {
                                        baseRate = rate / conversion;
                                    } else {
                                        baseRate = rate;
                                    }

                                    console.log(baseRate ,rate, rate / conversion);

                                }

                                $set('rate', Number(baseRate.toFixed(2)));

                                const qty = parseFloat($get('qty')) || 0;
                                $set('total', qty * baseRate);

                                const items = $get('../../items') ?? {};
                                const grandTotal = Object.values(items).reduce((sum, item) => {
                                    return sum + (parseFloat(item.total) || 0);
                                }, 0);

                                $set('../../grand_total', grandTotal);
                            JS)
            // ->helperText(function (Get $get) use ($productsKeyedArray) {
            //     $productId = $get('product_id');
            //     $selectedUnitId = $get('unit_id');

            //     $product = $productsKeyedArray[$productId] ?? null;
            //     if (!$product) {
            //         return;
            //     }

            //     $unitId = $product['unit_id'] ?? null;
            //     $subUnitId = $product['sub_unit_id'] ?? null;
            //     $conversion = $product['sub_unit_conversion'] ?? 0;

            //     $stock = $product['current_outlet_stock'] ?? 0;
            //     $stockInSubUnit = $stock * $conversion;

            //     if ($selectedUnitId === $unitId) {
            //         return $stock;
            //     } elseif ($selectedUnitId === $subUnitId) {
            //         return $stockInSubUnit;
            //     }

            //     return '';
            // })
            ->required();
    }
}
