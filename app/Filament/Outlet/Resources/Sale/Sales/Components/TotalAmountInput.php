<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class TotalAmountInput
{
    public static function make()
    {
        return TextInput::make('total')
            ->label('Total Amount')
            ->currency()
            ->disabled()
            ->saved()
            ->minValue(0)
            ->rules('min:0')
            ->required()
            ->dehydrateStateUsing(function ($state, Get $get) {
                $items = $get('items') ?? [];
                $total = 0;

                foreach ($items as $item) {
                    $qty = $item['qty'] ?? 0;
                    $rate = $item['rate'] ?? 0;
                    $total += $qty * $rate;
                }

                return $total;
            });
    }
}
