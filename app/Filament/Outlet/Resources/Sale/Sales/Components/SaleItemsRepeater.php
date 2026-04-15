<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Enums\DiscountType;
use App\Filament\Outlet\Resources\Sale\Sales\Components\ProductSelect;
use App\Filament\Outlet\Resources\Sale\Sales\Components\QuantityInput;
use App\Filament\Outlet\Resources\Sale\Sales\Components\UnitSelect;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SaleItemsRepeater
{
    public static function make($productsKeyedArray)
    {
        return Repeater::make('items')
            ->relationship('items')
            ->statePath('items')
            ->minItems(fn($operation) => $operation == 'edit' ? 0 : 1)
            ->columnSpanFull()
            ->columns(6)
            ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
            ->table([
                TableColumn::make('Product'),
                TableColumn::make('Unit'),
                TableColumn::make('Quantity'),
                TableColumn::make('Rate'),
                // TableColumn::make('Discount Type'),
                // TableColumn::make('Discount Value'),
                TableColumn::make('Total'),
            ])
            // ->reorderable()
            ->compact()
            ->schema([
                // ProductSelect::make(),
                Select::make('product_id')
                    // ->options(Product::pluck('name', 'id')->toArray())
                    ->relationship(
                        'product',
                        'name',
                        modifyQueryUsing: fn(Builder $query, $search) => $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhereHas('parent', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('unit', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('unit', fn($q) => $q->where('symbol', 'like', "%{$search}%"))
                            ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    )
                    // ->disableOptionWhen(function ($value, $state, $get) {
                    //     $selected = collect($get('../../items'))
                    //         ->pluck('product_id')
                    //         ->filter()
                    //         ->toArray();

                    //     return in_array($value, $selected) && $state != $value;
                    // })
                    ->distinct()
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name)
                    ->optionsLimit(20)
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
                    ->required(),
                UnitSelect::make($productsKeyedArray),
                QuantityInput::make($productsKeyedArray),
                TextInput::make('rate')
                    ->numeric()
                    ->required()
                    ->currency()
                    ->afterStateUpdatedJs(SaleForm::calculateTotals())
                    ->calculator()
                    ->minValue(1)
                    ->step(0.01),
                // Select::make('discount_type')
                //      ->options(DiscountType::class)
                //     ->default(DiscountType::FIXED->value)
                //     ->hidden()
                //     ->afterStateUpdatedJs(self::calculateTotals())
                //     ->required(),
                // TextInput::make('discount_value')
                //     ->required()
                //     ->hidden()
                //     ->default(0)
                //     ->minValue(0)
                //     ->maxValue(function (Set $set, Get $get) {
                //         $discountType = $get('discount_type');

                //         if ($discountType === DiscountType::PERCENT->value) {
                //             return 100;
                //         }

                //         return null;
                //     })
                //     ->afterStateUpdatedJs(self::calculateTotals())
                //     ->numeric(),
                TextInput::make('total')
                    ->numeric()
                    ->disabled()
                    ->saved()
                    ->dehydrateStateUsing(function ($state, callable $get) {
                        $qty = (float) ($get('qty') ?? 0);
                        $rate = (float) ($get('rate') ?? 0);

                        // $discountType = $get('discount_type') ?? DiscountType::FIXED;
                        // $discountValue = (float) $get('discount_value');

                        // $total = $qty * $rate;

                        // if ($discountType === DiscountType::PERCENT) {
                        //     $total = ($total * $discountValue / 100);
                        // } elseif ($discountType === DiscountType::FIXED) {
                        //     $total -= $discountValue;
                        // }

                        return $qty * $rate;
                    })
                    ->currency(),
            ]);
    }
}
