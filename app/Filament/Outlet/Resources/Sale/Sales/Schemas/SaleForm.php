<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Schemas;

use Closure;
use App\Enums\DiscountType;
use Filament\Schemas\Schema;
use App\Models\Master\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Repeater\TableColumn;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        $products = Product::with('unit')->withOutletStock()->get(['id', 'name', 'cost_price', 'unit_id']);

        $productsKeyedArray = $products->keyBy('id')->toArray();

        return $schema
            ->components([
                Hidden::make('products')
                    ->default(fn() => $productsKeyedArray)
                    ->dehydrated(false),
                // Group::make()
                //     ->columnSpanFull()
                //     ->columns(3)
                //     ->schema([
                Section::make()
                    // ->columnSpan(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Select::make('discount_type')
                            ->options(options: [
                                DiscountType::FIXED->value => ucfirst(DiscountType::FIXED->value),
                                DiscountType::PERCENT->value => ucfirst(DiscountType::PERCENT->value),
                            ])
                            ->default(DiscountType::FIXED->value)
                            ->afterStateUpdatedJs(<<<'JS'
                                const saleDiscountType  = $get('discount_type');
                                const saleDiscountValue = parseFloat($get('discount_value')) || 0;

                                const items = $get('items') ?? {};

                                let grandTotal = Object.values(items).reduce((sum, item) => {
                                    return sum + (parseFloat(item.total) || 0);
                                }, 0);

                                let saleGrandTotal = grandTotal;

                                if (saleDiscountType === 'percent') {
                                    saleGrandTotal -= (saleGrandTotal * saleDiscountValue / 100);
                                }

                                if (saleDiscountType === 'fixed') {
                                    saleGrandTotal -= saleDiscountValue;
                                }

                                saleGrandTotal = Math.max(saleGrandTotal, 0);

                                $set('total', grandTotal);
                                $set('grand_total', saleGrandTotal);
                            JS)
                            ->required(),
                        TextInput::make('discount_value')
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(function (Set $set, Get $get) {
                                $discountType = $get('discount_type');

                                if ($discountType === DiscountType::PERCENT->value) {
                                    return 100;
                                }

                                return null;
                            })
                            ->afterStateUpdatedJs(<<<'JS'
                                const saleDiscountType  = $get('discount_type');
                                const saleDiscountValue = parseFloat($get('discount_value')) || 0;

                                const items = $get('items') ?? {};

                                let grandTotal = Object.values(items).reduce((sum, item) => {
                                    return sum + (parseFloat(item.total) || 0);
                                }, 0);

                                let saleGrandTotal = grandTotal;

                                if (saleDiscountType === 'percent') {
                                    saleGrandTotal -= (saleGrandTotal * saleDiscountValue / 100);
                                }

                                if (saleDiscountType === 'fixed') {
                                    saleGrandTotal -= saleDiscountValue;
                                }

                                saleGrandTotal = Math.max(saleGrandTotal, 0);

                                $set('total', grandTotal);
                                $set('grand_total', saleGrandTotal);
                            JS)
                            ->numeric(),
                        TextInput::make('total')
                            ->label('Total Amount')
                            ->readonly()
                            ->dehydrated()
                            ->currency()
                            ->required(),
                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->readonly()
                            ->dehydrated()
                            ->currency()
                            ->required(),
                    ]),
                // ]),
                Repeater::make('items')
                    ->relationship('items')
                    ->statePath('items')
                    ->minItems(1)
                    ->columnSpanFull()
                    ->columns(6)
                    ->table([
                        TableColumn::make('Product'),
                        TableColumn::make('Quantity'),
                        TableColumn::make('Rate'),
                        TableColumn::make('Discount Type'),
                        TableColumn::make('Discount Value'),
                        TableColumn::make('Total'),
                    ])
                    ->reorderable()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            // ->options($productsPluckedArray)
                            ->disableOptionWhen(function ($value, $state, $get) {
                                $selected = collect($get('../../items'))
                                    ->pluck('product_id')
                                    ->filter()
                                    ->toArray();

                                return in_array($value, $selected) && $state != $value;
                            })
                            ->afterStateUpdatedJs(<<<'JS'
                                const productId = $state;
                                const products = $get('../../products') ?? {};

                                const rate = parseFloat(products[productId]?.cost_price || 0);
                                $set('rate', rate);

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
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            // ->suffix(function(Get $get) use ($productsKeyedArray){
                            //     $productId = $get('product_id');
                            //     return $productsKeyedArray[$productId]['unit']['symbol'] ?? null;
                            // })
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->default(0)
                            ->minValue(1)
                            ->rules(function (Get $get) use ($productsKeyedArray) {
                                return [
                                    'required',
                                    'numeric',
                                    'min:1',
                                    function (string $attribute, $value, Closure $fail) use ($get, $productsKeyedArray) {
                                        $productId = $get('product_id');
                                        $stock = $productsKeyedArray[$productId]['current_outlet_stock'] ?? 0;

                                        if ($value > $stock) {
                                            $fail("Low stock — only {$stock} available");
                                        }
                                    },
                                ];
                            })
                            // ->helperText(function (Get $get) use ($productsKeyedArray) {
                            //     $productId = $get('product_id');
                            //     if (!$productId) return;
                            //     $productStock = $productsKeyedArray[$productId]['current_outlet_stock'] ?? 0;
                            //     $unit = $productsKeyedArray[$productId]['unit']['symbol'] ?? null;
                            //     return "$productStock $unit";
                            // })
                            ->step(1),
                        TextInput::make('rate')
                            ->numeric()
                            ->required()
                            ->currency()
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->minValue(1)
                            ->step(1),
                        Select::make('discount_type')
                            ->options(options: [
                                DiscountType::FIXED->value => ucfirst(DiscountType::FIXED->value),
                                DiscountType::PERCENT->value => ucfirst(DiscountType::PERCENT->value),
                            ])
                            ->default(DiscountType::FIXED->value)
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->required(),
                        TextInput::make('discount_value')
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(function (Set $set, Get $get) {
                                $discountType = $get('discount_type');

                                if ($discountType === DiscountType::PERCENT->value) {
                                    return 100;
                                }

                                return null;
                            })
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->numeric(),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->currency(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('description')
                            ->nullable()
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function calculateTotals(): string
    {
        return <<<'JS'
        const qty  = parseFloat($get('qty')) || 0;
        const rate = parseFloat($get('rate')) || 0;

        const discountType  = $get('discount_type');
        const discountValue = parseFloat($get('discount_value')) || 0;

        let lineTotal = qty * rate;

        if (discountType === 'percent') {
            lineTotal -= (lineTotal * discountValue / 100);
        }

        if (discountType === 'fixed') {
            lineTotal -= discountValue;
        }

        lineTotal = Math.max(lineTotal, 0);

        $set('total', lineTotal);

        // calculate grand total of all items
        const items = $get('../../items') ?? {};
        let grandTotal = Object.values(items).reduce((sum, item) => {
            return sum + (parseFloat(item.total) || 0);
        }, 0);

        // apply invoice-level discount
        const saleDiscountType  = $get('../../discount_type');
        const saleDiscountValue = parseFloat($get('../../discount_value')) || 0;

        let saleGrandTotal = grandTotal;

        if (saleDiscountType === 'percent') {
            saleGrandTotal -= (saleGrandTotal * saleDiscountValue / 100);
        }

        if (saleDiscountType === 'fixed') {
            saleGrandTotal -= saleDiscountValue;
        }

        saleGrandTotal = Math.max(saleGrandTotal, 0);

        // set totals
        $set('../../total', grandTotal);
        $set('../../grand_total', saleGrandTotal);
    JS;
    }
}
