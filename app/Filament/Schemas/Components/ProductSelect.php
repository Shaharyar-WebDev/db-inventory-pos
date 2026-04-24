<?php

namespace App\Filament\Schemas\Components;

use App\Models\Master\Product;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class ProductSelect
{
    public static function make()
    {
        return Select::make('product_id')
            ->relationship(
                'product',
                'name',
            )
            ->getSearchResultsUsing(function (?string $search) {
                $query = Product::query()
                    ->where(function ($q) use ($search) {
                        return $q->where('name', 'like', "%{$search}%")
                            ->orWhereHas('parent', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('unit', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('symbol', 'like', "%{$search}%"))
                            ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($product) => [$product->id => $product->full_name]);

                return $query;
            })
            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name);
    }
}
