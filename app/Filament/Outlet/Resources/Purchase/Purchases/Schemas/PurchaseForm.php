<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Schemas;

use App\Filament\Outlet\Resources\Sale\Sales\Components\DiscountAmountInput;
use App\Filament\Outlet\Resources\Sale\Sales\Components\DiscountTypeSelect;
use App\Filament\Outlet\Resources\Sale\Sales\Components\DiscountValueInput;
use App\Filament\Outlet\Resources\Sale\Sales\Components\GrandTotalInput;
use App\Filament\Outlet\Resources\Sale\Sales\Components\TotalAmountInput;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use App\Models\Master\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        $products = Product::with('unit')->get(['id', 'name', 'cost_price', 'unit_id']);

        $productsKeyedArray = $products->keyBy('id')->toArray();
        // $productsPluckedArray = $products->pluck('name', 'id')->toArray();

        return $schema
            ->components([
                Hidden::make('products')
                    ->afterStateHydrated(fn(Set $set) => $set('products', $productsKeyedArray))
                    ->dehydrated(false),
                Group::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ]),
                Section::make()
                    ->columnSpan(2)
                    ->columnSpanFull()
                    ->schema([
                        // TextInput::make('grand_total')
                        //     ->label('Total Amount')
                        //     ->numeric()
                        //     ->readonly()
                        //     ->saved()
                        //     ->dehydrateStateUsing(function (Get $get) {
                        //         $items = $get('items') ?? [];
                        //         $total = 0;
                        //         foreach ($items as $item) {
                        //             $qty  = $item['qty'] ?? 0;
                        //             $rate = $item['rate'] ?? 0;

                        //             $total += ($qty * $rate);
                        //         }
                        //         return $total;
                        //     })
                        //     ->currency(),

                        Group::make()
                            ->columnSpanFull()
                            ->columns(4)
                            ->schema([
                                TotalAmountInput::make(),
                                DiscountTypeSelect::make(),
                                DiscountValueInput::make(),
                                DiscountAmountInput::make(),
                            ]),

                        Group::make()
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                TextInput::make('delivery_charges')
                                    ->currency()
                                    ->minValue(0)
                                    ->rules('min:0')
                                    ->calculator()
                                    ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
                                    ->required(),
                                TextInput::make('tax_charges')
                                    ->currency()
                                    ->minValue(0)
                                    ->rules('min:0')
                                    ->calculator()
                                    ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
                                    ->required(),
                                GrandTotalInput::make(),
                            ]),
                    ]),
                Repeater::make('items')
                    ->relationship('items')
                    ->statePath('items')
                    ->minItems(fn($operation) => $operation == 'edit' ? 0 : 1)
                    ->columnSpanFull()
                    ->columns(4)
                    // ->afterStateUpdatedJs(<<<'JS'
                    //     const items = $get('items') ?? {};
                    //     let grandTotal = 0;

                    //     Object.keys(items).forEach(key => {
                    //         const item = items[key];
                    //         const qty  = parseFloat(item.qty) || 0;
                    //         const rate = parseFloat(item.rate) || 0;
                    //         item.total = qty * rate;
                    //         grandTotal += item.total;
                    //     });

                    //     $set('items', items);
                    //     $set('grand_total', grandTotal);
                    // JS)
                    ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
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
                            // ->afterStateUpdatedJs(self::updateGrandTotals())
                            ->afterStateUpdatedJs(SaleForm::calculateTotals())
                            ->default(0)
                            ->minValue(fn($operation) => $operation === "edit" ? 0 : 1),
                        TextInput::make('rate')
                            ->required()
                            ->currency()
                            ->calculator()
                            // ->afterStateUpdatedJs(self::updateGrandTotals())
                            ->afterStateUpdatedJs(SaleForm::calculateTotals())
                            ->minValue(1)
                            ->step(0.01),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->dehydrateStateUsing(function (Get $get) {
                                $rate = (float) $get('rate');
                                $qty  = (float) $get('qty');

                                return ($qty * $rate);
                            })
                            ->currency(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('Attachments')
                            ->multiple()
                            ->directory('attachments/purchase')
                            ->disk('public')
                            ->visibility('public')
                            ->deleteUploadedFileUsing(function ($file) {
                                Storage::disk('public')->delete($file);
                            })
                            ->nullable()
                            ->downloadable()
                            ->columnSpanFull()
                            ->openable(),
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
}
