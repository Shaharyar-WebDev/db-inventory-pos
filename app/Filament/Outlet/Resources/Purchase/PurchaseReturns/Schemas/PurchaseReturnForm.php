<?php

namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Schemas;

use Closure;
use Filament\Schemas\Schema;
use App\Models\Master\Product;
use App\Models\Purchase\Purchase;
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

class PurchaseReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        $products = Product::with('unit')->get(['id', 'name', 'cost_price', 'unit_id']);

        $productsKeyedArray = $products->keyBy('id')->toArray();

        return $schema
            ->components([
                Hidden::make('products')
                    ->afterStateHydrated(fn(Set $set) => $set('products', $productsKeyedArray))
                    ->dehydrated(false),
                Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Select::make('purchase_id')
                                    ->relationship('purchase', 'purchase_number')
                                    ->columnSpanFull()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ]),
                        Section::make()
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('grand_total')
                                    ->label('Total Amount')
                                    ->numeric()
                                    ->readonly()
                                    ->dehydrated()
                                    ->currency(),
                            ]),
                    ]),
                Repeater::make('items')
                    ->relationship('items')
                    ->statePath('items')
                    ->minItems(1)
                    ->columnSpanFull()
                    ->addable(false)
                    ->deletable(false)
                    ->columns(4)
                    ->table([
                        TableColumn::make('Product'),
                        TableColumn::make('Quantity'),
                        TableColumn::make('Rate'),
                        TableColumn::make('Total'),
                    ])
                    ->reorderable()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
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

                                console.log('Selected product ID:', productId);
                                console.log('Products data:', products);

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
                            ->required(),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            // ->suffix(function(Get $get) use ($productsKeyedArray){
                            //     $productId = $get('product_id');
                            //     return $productsKeyedArray[$productId]['unit']['symbol'] ?? null;
                            // })
                            ->afterStateUpdatedJs(self::updateGrandTotals())
                            ->default(0)
                            ->rules(function (Get $get) {
                                return [
                                    function (string $attribute, $value, Closure $fail) use ($get) {

                                        $productId  = $get('product_id');
                                        $purchaseId = $get('../../purchase_id');

                                        if (! $productId || ! $purchaseId) {
                                            return;
                                        }

                                        $purchase = Purchase::with([
                                            'items',
                                            'purchaseReturns.items',
                                        ])->find($purchaseId);

                                        if (! $purchase) {
                                            return;
                                        }

                                        $purchaseItem = $purchase->items
                                            ->firstWhere('product_id', $productId);

                                        if (! $purchaseItem) {
                                            return;
                                        }

                                        // SUM of all previously returned qty for this product
                                        $alreadyReturnedQty = $purchase->purchaseReturns
                                            ->flatMap->items
                                            ->where('product_id', $productId)
                                            ->sum('qty');

                                        $remainingQty = $purchaseItem->qty - $alreadyReturnedQty;

                                        if ($value > $remainingQty) {
                                            $fail(
                                                "Return quantity cannot exceed remaining quantity of {$remainingQty}."
                                            );
                                        }
                                    }
                                ];
                            })
                            ->minValue(fn($operation) => $operation === "edit" ? 0 : 1)
                            ->step(1),
                        TextInput::make('rate')
                            ->required()
                            ->currency()
                            ->disabled()
                            ->dehydrated()
                            ->afterStateUpdatedJs(self::updateGrandTotals())
                            ->minValue(1)
                            ->step(0.01),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->currency(),
                    ])
                    ->default(self::getDefaultRepeaterData()),
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

    public static function updateGrandTotals(): string
    {
        return <<<'JS'
                const qty  = parseFloat($get('qty')) || 0;
                const rate = parseFloat($get('rate')) || 0;

                $set('total', qty * rate);

                const items = $get('../../items') ?? {};
                const grandTotal = Object.values(items).reduce((sum, item) => {
                    return sum + (parseFloat(item.total) || 0);
                }, 0);

                $set('../../grand_total', grandTotal);
            JS;
    }

    // public static function getDefaultRepeaterData()
    // {
    //     $purchaseeId = request()->query('purchase_id');
    //     $purchase = Purchase::with('items')->find($purchaseeId);
    //     if (!$purchase) {
    //         return [];
    //     }

    //     return $purchase->items->map(function ($item) {
    //         return [
    //             'product_id' => $item->product_id,
    //             'qty' => (float) $item->qty,
    //             'rate' => (float) $item->rate,
    //             'total' => (float) $item->total,
    //         ];
    //     });
    // }

    public static function getDefaultRepeaterData()
    {
        $purchaseId = request()->query('purchase_id');

        $purchase = Purchase::with([
            'items',
            'purchaseReturns.items',
        ])->find($purchaseId);

        if (! $purchase) {
            return [];
        }

        return $purchase->items
            ->map(function ($item) use ($purchase) {

                $alreadyReturnedQty = $purchase->purchaseReturns
                    ->flatMap->items
                    ->where('product_id', $item->product_id)
                    ->sum('qty');

                $remainingQty = $item->qty - $alreadyReturnedQty;

                // Do NOT show fully returned items
                if ($remainingQty <= 0) {
                    return null;
                }

                return [
                    'product_id' => $item->product_id,
                    'qty'        => (float) $remainingQty, // 👈 important
                    'rate'       => (float) $item->rate,
                    'total'      => (float) ($remainingQty * $item->rate),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
