<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Enums\DiscountType;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class GrandTotalInput
{
    public static function make()
    {
        return  TextInput::make('grand_total')
            ->label('Grand Total')
            ->currency()
            ->disabled()
            ->saved()
            ->minValue(0)
            ->rules('min:0')
            ->required()
            ->dehydrateStateUsing(function ($state, Get $get) {
                $items = $get('items') ?? [];
                $total = 0;
                $deliveryCharges = (float) $get('delivery_charges');
                $taxCharges      = (float) $get('tax_charges');
                $discountType = $get('discount_type') ?? DiscountType::FIXED;
                $discountValue = (float) $get('discount_value');

                foreach ($items as $item) {
                    $qty = $item['qty'] ?? 0;
                    $rate = $item['rate'] ?? 0;
                    $total += $qty * $rate;
                }

                if ($discountType === DiscountType::PERCENT) {
                    $total -= ($total * $discountValue / 100);
                } elseif ($discountType === DiscountType::FIXED) {
                    $total -= $discountValue;
                }

                $grandTotal = $total + $deliveryCharges + $taxCharges;

                return $grandTotal;
            });
    }
}
