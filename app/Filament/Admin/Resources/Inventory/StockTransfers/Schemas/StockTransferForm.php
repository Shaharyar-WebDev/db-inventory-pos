<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Schemas;

use Closure;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\Inventory\InventoryLedger;
use Filament\Schemas\Components\Utilities\Get;

class StockTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        $products = InventoryLedger::query()
            ->lockForUpdate()
            ->selectRaw('product_id, outlet_id, SUM(qty) as qty')
            ->groupBy('product_id', 'outlet_id')
            ->get()
            ->groupBy('product_id')
            ->map(fn($rows) => $rows->keyBy('outlet_id'));

        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('from_outlet_id')
                            ->relationship('fromOutlet', 'name')
                            ->disableOptionWhen(fn($value, $get) => $value == $get('to_outlet_id'))
                            ->disabledOn('edit')
                            ->required(),
                        Select::make('to_outlet_id')
                            ->relationship('toOutlet', 'name')
                            ->disableOptionWhen(fn($value, $get) => $value == $get('from_outlet_id'))
                            ->disabledOn('edit')
                            ->required(),
                    ]),
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
                            ->rules(fn(Get $get) => [
                                'min:0',
                                'required',
                                function (string $attribute, $value, Closure $fail) use ($get, $products) {
                                    $productId = $get('product_id');
                                    $outletId = $get('../../from_outlet_id');
                                    $stock = $products[$productId][$outletId]['qty'] ?? 0;

                                    if ($value > $stock) {
                                        $fail("Low stock — only {$stock} available");
                                    }
                                }
                            ])
                            ->minValue(0),
                    ])
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
