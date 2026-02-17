<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Enums\DiscountType;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class DiscountValueInput
{
    public static function make()
    {
        return TextInput::make('discount_value')
            ->required()
            ->default(0)
            ->minValue(0)
            ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
            ->rules(function (Get $get) {
                return [
                    function (string $attribute, $value, Closure $fail) use ($get) {
                        $discountType = $get('discount_type');
                        $discountValue = $get('discount_value');
                        $deliveryCharges = $get('delivery_charges') ?? 0;
                        $taxCharges      = $get('tax_charges') ?? 0;

                        if ($discountType === DiscountType::PERCENT) {
                            if ($discountValue < 0 || $discountValue > 100) {
                                $fail('Percent discount must be between 0 and 100.');
                            }
                        } else { // FIXED
                            if ($discountValue < 0) {
                                $fail('Fixed discount cannot be negative.');
                            }
                        }

                        $items = $get('items') ?? [];
                        $total = 0;

                        foreach ($items as $item) {
                            $total += $item['total'];
                        }

                        if ($discountType === DiscountType::PERCENT) {
                            $discountAmount = ($total * $discountValue) / 100;
                        } elseif ($discountType === DiscountType::FIXED) {
                            $discountAmount = $discountValue;
                        }

                        $grandTotal = $deliveryCharges + $taxCharges + $total;

                        if ($discountAmount > $grandTotal) {
                            $fail("Discount amount cannot be greater than total amount Rs $grandTotal");
                        }
                    }
                ];
            })
            ->numeric();
    }
}
