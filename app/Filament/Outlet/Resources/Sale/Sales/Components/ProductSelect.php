<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Models\Master\Product;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class ProductSelect
{
    public static function make()
    {
        return Select::make('product_id')
            ->relationship(
                'product',
                'name',
            )
            ->getSearchResultsUsing(function (?string $search) {
                $query = Product::query()
                    ->where(function ($q) use ($search) {
                        return $q->where('name', 'like', "%{$search}%")
                            ->orWhereHas('parent', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('unit', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('symbol', 'like', "%{$search}%"))
                            ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($product) => [$product->id => $product->full_name]);

                return $query;
            })
            ->disableOptionWhen(function ($value, $state, $get) {
                $selected = collect($get('../../items'))
                    ->pluck('product_id')
                    ->filter()
                    ->toArray();

                return in_array($value, $selected) && $state != $value;
            })
            ->distinct()
            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name)
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
            ->required();
    }
}
