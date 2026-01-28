<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas;

use App\Models\Master\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class InventoryAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('items')
                    ->relationship('items')
                    ->reorderable()
                    // ->collapsible()
                    ->columns(2)
                    ->minItems(1)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->disableOptionWhen(
                                function ($value, $state, $get) {
                                    $selected = collect($get('../../items'))
                                        ->pluck('product_id')
                                        ->filter()
                                        ->toArray();

                                    return in_array($value, $selected) && $state != $value;
                                }
                            )
                            ->required(),
                        TextInput::make('qty')
                            ->required()
                            ->numeric()
                            // ->minValue(1),
                    ])
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
