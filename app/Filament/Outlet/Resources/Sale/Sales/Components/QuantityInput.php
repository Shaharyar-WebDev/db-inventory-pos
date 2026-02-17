<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Model;

class QuantityInput
{
    public static function make($productsKeyedArray)
    {
        return TextInput::make('qty')
            ->label('Quantity')
            ->numeric()
            ->required()
            ->afterStateUpdatedJs(SaleForm::calculateTotals())
            ->default(0)
            ->minValue(fn($operation) => $operation === "edit" ? 0 : 1)
            ->rules(function (Get $get, ?Model $record) use ($productsKeyedArray) {
                return [
                    'required',
                    'numeric',
                    fn($operation) => $operation === "edit" ? 'min:0' : 'min:1',
                    function (string $attribute, $value, Closure $fail) use ($get, $productsKeyedArray, $record) {
                        $productId = $get('product_id');
                        $selectedUnitId = $get('unit_id');
                        $validateConversion = function ($conversion) use ($fail) {
                            if ($conversion <= 0) {
                                $fail("Invalid sub unit conversion, please define conversion in product");
                                return false;
                            }
                            return true;
                        };

                        $product = $productsKeyedArray[$productId] ?? null;

                        if (!$product) {
                            return;
                        }

                        $stock = 0;
                        $productStock = (float) $product['current_outlet_stock'] ?? 0;

                        $unitId = $product['unit_id'] ?? null;
                        $subUnitId = $product['sub_unit_id'] ?? null;
                        $conversion = (float) $product['sub_unit_conversion'] ?? 0;
                        $unitName = '';

                        if ($selectedUnitId == $unitId) {
                            $stock = $productStock;
                            $unitName = $product['unit']['symbol'];
                        } elseif ($selectedUnitId == $subUnitId) {
                            if (!$validateConversion($conversion)) return;
                            $stock = $productStock * $conversion;
                            $unitName = $product['sub_unit']['symbol'];
                        }

                        if ($value > $stock) {
                            $fail("Low stock — only {$stock} {$unitName} available.");
                        }
                    },
                ];
            })
            ->step(1);
    }
}
