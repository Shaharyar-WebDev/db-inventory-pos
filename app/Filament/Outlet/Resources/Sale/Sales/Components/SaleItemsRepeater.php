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

class SaleItemsRepeater
{
    public static function make($productsKeyedArray)
    {
        return   Repeater::make('items')
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
            ->reorderable()
            ->compact()
            ->schema([
                ProductSelect::make(),
                UnitSelect::make($productsKeyedArray),
                QuantityInput::make($productsKeyedArray),
                TextInput::make('rate')
                    ->numeric()
                    ->required()
                    ->currency()
                    ->afterStateUpdatedJs(SaleForm::calculateTotals())
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
