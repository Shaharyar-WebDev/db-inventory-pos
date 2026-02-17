<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Components;

use App\Enums\DiscountType;
use App\Filament\Outlet\Resources\Sale\Sales\Schemas\SaleForm;
use Filament\Forms\Components\Select;

class DiscountTypeSelect
{
    public static function make()
    {
        return Select::make('discount_type')
            ->options(DiscountType::class)
            ->default(DiscountType::FIXED)
            ->afterStateUpdatedJs(SaleForm::calculateGrandTotal())
            ->required()
            ->searchable()
            ->preload(false)
            ->optionsLimit(0)
            ->native(false);
    }
}
