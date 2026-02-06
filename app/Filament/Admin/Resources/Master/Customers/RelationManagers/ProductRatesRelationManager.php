<?php

namespace App\Filament\Admin\Resources\Master\Customers\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Product;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\RelationManagers\RelationManager;

class ProductRatesRelationManager extends RelationManager
{
    protected static string $relationship = 'productRates';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['selling_price'])
                    ->required(),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->currency()
                    ->helperText(function (Get $get) {
                        $productId = $get('product_id');
                        if (!$productId) return;
                        $product = Product::find($productId);
                        if ($product) {
                            $sellingPrice = $product->selling_price ?? 0;
                            return "Product Selling Price: " . currency_format($sellingPrice);
                        }
                    })
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                TextColumn::make('product.name')
                    ->copyable(),
                TextColumn::make('selling_price')
                    ->copyable()
                    ->currency(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
