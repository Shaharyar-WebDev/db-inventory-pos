<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Enums\DiscountType;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class DiscountAmountInput
{
    public static function make()
    {
        return TextInput::make('discount_amount')
            ->currency()
            ->minValue(0)
            ->rules('min:0')
            ->disabled()
            ->saved()
            ->required()
            ->dehydrateStateUsing(function (Get $get) {
                $items = $get('items') ?? [];
                $total = 0;
                $discountType = $get('discount_type') ?? DiscountType::FIXED;
                $discountValue = (float) $get('discount_value');
                $discountAmount = 0;

                foreach ($items as $item) {
                    $qty = $item['qty'] ?? 0;
                    $rate = $item['rate'] ?? 0;
                    $total += $qty * $rate;
                }

                if ($discountType === DiscountType::PERCENT) {
                    $discountAmount = ($total * $discountValue / 100);
                } elseif ($discountType === DiscountType::FIXED) {
                    $discountAmount = $discountValue;
                }

                return $discountAmount;
            });
    }
}
