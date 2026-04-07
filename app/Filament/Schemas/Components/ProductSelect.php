<?php

namespace App\Filament\Schemas\Components;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductSelect
{
    public static function make()
    {
        return Select::make('product_id')
            ->relationship('product', 'name',    modifyQueryUsing: fn(Builder $query, $search) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhereHas('parent', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('unit', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('unit', fn($q) => $q->where('symbol', 'like', "%{$search}%")))
            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name);
    }
}
