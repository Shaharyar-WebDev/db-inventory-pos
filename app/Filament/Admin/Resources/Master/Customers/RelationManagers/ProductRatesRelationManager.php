<?php
namespace App\Filament\Admin\Resources\Master\Customers\RelationManagers;

use App\Models\Master\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ProductRatesRelationManager extends RelationManager
{
    protected static string $relationship = 'productRates';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
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
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name)
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['selling_price'])
                    ->rule(function ($record) {
                        return Rule::unique('customer_product_rates', 'product_id')
                            ->where('customer_id', $this->ownerRecord->id)
                            ->ignore($record?->id);
                    })
                    ->required(),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->currency()
                    ->helperText(function (Get $get) {
                        $productId = $get('product_id');
                        if (! $productId) {
                            return;
                        }

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
                TextColumn::make('product.full_name')
                    ->searchable(['product.name', 'product.brand.name', 'product.unit.name', 'product.unit.symbol', 'product.category.name', 'product.parent.name'])
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
