<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Schemas;

use App\Models\Master\Product;
use Carbon\Unit;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
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
                    ->columns(3)
                    ->table([
                        TableColumn::make('Product'),
                        TableColumn::make('Quantity'),
                    ])
                    ->reorderable()
                    ->addable(fn($operation) => $operation !== 'edit')
                    ->minItems(fn($operation) => $operation === 'edit' ? 0 : 1)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->disabledOn('edit')
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
                        // ->minValue(1)
                        ,
                    ])
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
