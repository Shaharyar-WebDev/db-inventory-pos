<?php

namespace App\Filament\Schemas\Components;

use Filament\Schemas\Components\Component;

class DiscountTypeSelect extends Component
{
    protected string $view = 'filament.schemas.components.discount-type-select';

    public static function make(): static
    {
        return app(static::class);
    }
}
