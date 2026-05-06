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
            // ->minValue(fn($operation) => $operation === "edit" ? 0 : 0.1)
            ->minValue(fn(string $operation) => $operation === 'edit' ? 0 : 0.001)
            ->rules(function (Get $get, ?Model $record, $livewire) use ($productsKeyedArray) {
                return [
                    'required',
                    'numeric',
                    fn($operation) => $operation === "edit" ? 'min:0' : 'gt:0',
                    function (string $attribute, float $value, Closure $fail) use ($get, $productsKeyedArray, $record, $livewire) {
                        $productId = (int) $get('product_id');
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
                        $productStock = (float) ($product['current_outlet_stock'] ?? 0);

                        $selectedUnitId = (int) $get('unit_id');
                        $unitId = (int) ($product['unit_id'] ?? null);
                        $subUnitId = $product['sub_unit_id'] ? (int) $product['sub_unit_id'] : null;
                        $conversion = (float) $product['sub_unit_conversion'] ?? 1;
                        $unitName = '';

                        if ($selectedUnitId === $unitId) {
                            $stock = $productStock;
                            $unitName = $product['unit']['symbol'];
                        } elseif ($subUnitId && $selectedUnitId === $subUnitId) {
                            if (!$validateConversion($conversion)) return;
                            $stock = $productStock * $conversion;
                            $unitName = $product['sub_unit']['symbol'];
                        }

                        if ($record) {
                            $originalRecord = $record->fresh();
                            $recordQty = (float) $originalRecord->qty;
                            $recordUnitId = (int) $originalRecord->unit_id;

                            if ($selectedUnitId === $unitId) {
                                $stock += ($recordUnitId === $unitId)
                                    ? $recordQty
                                    : $recordQty / $conversion;
                            } elseif ($subUnitId && $selectedUnitId === $subUnitId) {
                                $stock += ($recordUnitId === $unitId)
                                    ? $recordQty * $conversion
                                    : $recordQty;
                            }
                        }

                        if ($value > $stock) {
                            $fail("Low stock — only {$stock} {$unitName} available.");
                        }
                    },
                ];
            });
    }
}
