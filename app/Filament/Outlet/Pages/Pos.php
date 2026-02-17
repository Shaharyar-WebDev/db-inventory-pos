<?php

namespace App\Filament\Outlet\Pages;

use Closure;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Enums\DiscountType;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Master\Product;
use App\Models\Master\Customer;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Flex;
use App\Support\Actions\RefreshAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Filament\Outlet\Resources\Master\Products\ProductResource;

class Pos extends Page implements HasTable, HasSchemas
{
    use InteractsWithTable, InteractsWithSchemas, HasPageShield;

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return false;
    }

    protected string $view = 'filament.outlet.pages.pos';

    protected static ?string $title = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public function getHeading(): ?string
    {
        return null;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductResource::getEloquentQuery())
            ->columns([
                Stack::make([
                    Panel::make([
                        // Thumbnail
                        ImageColumn::make('thumbnail')
                            ->label('Thumbnail')
                            ->circular()
                            ->disk('public')
                            ->imageSize(140)
                            ->placeholder('---')
                            ->toggleable(false)
                            ->extraAttributes(['class' => 'table-song-card']),

                        // Name
                        TextColumn::make('name')
                            ->label('Name')
                            ->copyable()
                            ->weight('bold')
                            ->toggleable(false)
                            ->extraAttributes(['class' => 'text-center text-lg mt-2']),

                        TextColumn::make('catgory_and_brand')
                            ->state(fn($record) => $record->category?->name . ' - ' . $record->brand?->name)
                            ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1'])
                            ->searchable(query: function ($query, $search) {
                                $query->whereHas('category', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                })->orWhereHas('brand', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                });
                            }),

                        // // Category
                        // TextColumn::make('category.name')
                        //     ->label('Category')
                        //     ->copyable()
                        //     ->toggleable(false)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // // Brand
                        // TextColumn::make('brand.name')
                        //     ->label('Brand')
                        //     ->copyable()
                        //     ->toggleable(false)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // Unit
                        // TextColumn::make('unit.name')
                        //     ->label('Unit')
                        //     ->copyable()
                        //     ->toggleable(false)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // Cost Price
                        // TextColumn::make('cost_price')
                        //     ->label('Cost Price')
                        //     ->currency()
                        //     ->copyable()
                        //     ->toggleable(false)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // Selling Price
                        TextColumn::make('selling_price')
                            ->label('Selling Price')
                            ->currency()
                            ->copyable()
                            ->toggleable(false)
                            ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // Current Stock
                        TextColumn::make('current_outlet_stock')
                            ->label('Stock')
                            ->default(0)
                            ->searchable(false)
                            ->suffix(fn($record) => ' ' . ($record->unit?->symbol ?? ''))
                            // ->action(ViewProductStockByOutletAction::make())
                            ->sortable(false)
                            ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // Current Value
                        // TextColumn::make('current_value')
                        //     ->label('Value')
                        //     ->currency()
                        //     ->searchable(false)
                        //     ->action(ViewProductValueByOutletAction::make())
                        //     ->sortable(false)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // // Deleted At
                        // TextColumn::make('deleted_at')
                        //     ->label('Deleted At')
                        //     ->dateTime()
                        //     ->toggleable(true)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // // Created At
                        // TextColumn::make('created_at')
                        //     ->label('Created At')
                        //     ->dateTime()
                        //     ->toggleable(true)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),

                        // // Updated At
                        // TextColumn::make('updated_at')
                        //     ->label('Updated At')
                        //     ->dateTime()
                        //     ->toggleable(true)
                        //     ->extraAttributes(['class' => 'text-center text-sm text-gray-500 mt-1']),
                    ])
                        ->collapsible(),
                ])
                    ->alignCenter()
                    ->extraAttributes([
                        'class' => 'text-center cursor-pointer',
                        // 'wire:click' => "selectProduct({$record->id})",
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->groupedRecordActions([
                // EditAction::make(),
                // ViewProductStockByOutletAction::make()
                //     ->icon('heroicon-o-eye')
                //     ->color('warning'),
                // ViewProductValueByOutletAction::make()
                //     ->icon('heroicon-o-eye')
                //     ->color('success'),
                // DeleteAction::make(),
                // RestoreAction::make(),
                // ForceDeleteAction::make(),
                // Action::make('export_ledger')
                //     ->icon('heroicon-o-document-text')
                //     ->color('info')
                //     ->schema([
                //         Select::make('outlet_id')
                //             ->label('Outlet')
                //             ->options(Outlet::options())
                //         // ->required(),
                //     ])
                //     ->action(function (Model $record, array $data) {
                //         $outletId = $data['outlet_id'];
                //         $outlet = Outlet::find($outletId);
                //         $suffix = $outlet ? "-{$outlet->name}" : '';
                //         $fileName = "inventory_ledger_{$record->name}{$suffix}.xlsx";
                //         return Excel::download(new InventoryLedgerExport(
                //             $record->id,
                //             $outletId,
                //         ), $fileName);
                //     }),
            ])
            ->columnManager(false)
            ->recordActions([
                Action::make('selectProduct')
                    ->label('Select')
                    ->color('primary')
                    ->action(fn($record) => $this->selectProduct($record->id))
                    ->button()
                    ->disabled(fn($record) => $record->current_outlet_stock <= 0)
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                //     ForceDeleteBulkAction::make(),
                //     RestoreBulkAction::make(),
                // ]),
                RefreshAction::make()
            ]);
    }

    public function form(Schema $schema): Schema
    {
        $products = Product::with('unit', 'customerRates')->withOutletStock()->get(['id', 'name', 'selling_price', 'unit_id']);

        $productsKeyedArray = $products->keyBy('id')->toArray();

        $productsPluckedArray = $products->pluck('name', 'id')->toArray();

        return $schema
            ->components([
                // Section::make()
                //     ->compact()
                //     ->schema([
                Hidden::make('products')
                    ->default(fn() => $productsKeyedArray)
                    ->dehydrated(false),
                // Group::make()
                //     ->columnSpanFull()
                //     ->columns(3)
                //     ->schema([
                Section::make()
                    // ->columnSpan(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('customer_id')
                            // ->relationship('customer', 'name')
                            ->label('Customer')
                            ->options(Customer::pluck('name', 'id')->toArray())
                            // ->createOptionForm(CustomerForm::configure($schema)->getComponents())
                            // ->createOptionUsing(function (array $data) {
                            //     $customer = Customer::updateOrcreate($data);
                            //     return $customer->id;
                            // })
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Select::make('discount_type')
                            ->options(options: [
                                DiscountType::FIXED->value => ucfirst(DiscountType::FIXED->value),
                                DiscountType::PERCENT->value => ucfirst(DiscountType::PERCENT->value),
                            ])
                            ->default(DiscountType::FIXED->value)
                            ->afterStateUpdatedJs(self::calculateGrandTotal())
                            ->required(),
                        TextInput::make('discount_value')
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(function (Set $set, Get $get) {
                                $discountType = $get('discount_type');

                                if ($discountType === DiscountType::PERCENT->value) {
                                    return 100;
                                }

                                return null;
                            })
                            ->afterStateUpdatedJs(self::calculateGrandTotal())
                            ->numeric(),
                        TextInput::make('total')
                            ->label('Total Amount')
                            ->readonly()
                            ->dehydrated()
                            ->currency()
                            ->required(),
                        TextInput::make('delivery_charges')
                            ->currency()
                            ->afterStateUpdatedJs(self::calculateGrandTotal())
                            ->required(),
                        TextInput::make('tax_charges')
                            ->currency()
                            ->afterStateUpdatedJs(self::calculateGrandTotal())
                            ->required(),
                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->readonly()
                            ->dehydrated()
                            ->currency()
                            ->required(),
                    ]),
                // ]),
                Repeater::make('items')
                    // ->relationship('items')
                    ->statePath('items')
                    ->minItems(1)
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->compact()
                    ->columns(6)
                    ->table([
                        TableColumn::make('Product'),
                        TableColumn::make('Quantity'),
                        TableColumn::make('Rate'),
                        // TableColumn::make('Discount Type'),
                        TableColumn::make('Discount'),
                        TableColumn::make('Total'),
                    ])
                    ->reorderable(false)
                    ->afterStateUpdatedJs(self::calculateGrandTotal())
                    ->schema([
                        Select::make('product_id')
                            // ->relationship('product', 'name')
                            ->label('Product')
                            ->options($productsPluckedArray)
                            ->disableOptionWhen(function ($value, $state, $get) {
                                $selected = collect($get('../../items'))
                                    ->pluck('product_id')
                                    ->filter()
                                    ->toArray();

                                return in_array($value, $selected) && $state != $value;
                            })
                            ->afterStateUpdatedJs(<<<'JS'
                                const productId = $get('product_id');
                                const customerId = $get('../../customer_id');
                                const products = $get('../../products') ?? {};

                                if (products[productId]) {
                                const customerRateObj = (products[productId].customer_rates || []).find(
                                    r => r.customer_id == customerId
                                );

                                if (customerRateObj) {
                                    rate = parseFloat(customerRateObj.selling_price);
                                } else {
                                    rate = parseFloat(products[productId].selling_price || 0);
                                }
                                }

                                // const rate = parseFloat(products[productId]?.selling_price || 0);
                                $set('rate', rate);

                                const qty = parseFloat($get('qty')) || 0;
                                $set('total', qty * rate);

                                const items = $get('../../items') ?? {};
                                const grandTotal = Object.values(items).reduce((sum, item) => {
                                    return sum + (parseFloat(item.total) || 0);
                                }, 0);

                                $set('../../grand_total', grandTotal);
                            JS)
                            // ->live()
                            // ->partiallyRenderComponentsAfterStateUpdated(['qty'])
                            ->required(),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            // ->suffix(function(Get $get) use ($productsKeyedArray){
                            //     $productId = $get('product_id');
                            //     return $productsKeyedArray[$productId]['unit']['symbol'] ?? null;
                            // })
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->default(0)
                            ->minValue(fn($operation) => $operation === "edit" ? 0 : 1)
                            ->rules(function (Get $get) use ($productsKeyedArray) {
                                return [
                                    'required',
                                    'numeric',
                                    fn($operation) => $operation === "edit" ? 'min:0' : 'min:1',
                                    function (string $attribute, $value, Closure $fail) use ($get, $productsKeyedArray) {
                                        $productId = $get('product_id');
                                        $stock = $productsKeyedArray[$productId]['current_outlet_stock'] ?? 0;

                                        if ($value > $stock) {
                                            $fail("Low stock — only {$stock} available");
                                        }
                                    },
                                ];
                            })
                            // ->helperText(function (Get $get) use ($productsKeyedArray) {
                            //     $productId = $get('product_id');
                            //     if (!$productId) return;
                            //     $productStock = $productsKeyedArray[$productId]['current_outlet_stock'] ?? 0;
                            //     $unit = $productsKeyedArray[$productId]['unit']['symbol'] ?? null;
                            //     return "$productStock $unit";
                            // })
                            ->step(1),
                        TextInput::make('rate')
                            ->numeric()
                            ->required()
                            ->currency()
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->minValue(1)
                            ->step(0.01),
                        // Select::make('discount_type')
                        //     ->options(options: [
                        //         DiscountType::FIXED->value => ucfirst(DiscountType::FIXED->value),
                        //         DiscountType::PERCENT->value => ucfirst(DiscountType::PERCENT->value),
                        //     ])
                        //     ->default(DiscountType::FIXED->value)
                        //     ->afterStateUpdatedJs(self::calculateTotals())
                        //     ->hidden()
                        //     ->required(),
                        Hidden::make('discount_type')
                            ->default(DiscountType::FIXED->value)
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->dehydrated(false),
                        TextInput::make('discount_value')
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->currency()
                            // ->maxValue(function (Set $set, Get $get) {
                            //     $discountType = $get('discount_type');

                            //     if ($discountType === DiscountType::PERCENT->value) {
                            //         return 100;
                            //     }

                            //     return null;
                            // })
                            ->afterStateUpdatedJs(self::calculateTotals())
                            ->numeric(),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->currency(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('description')
                            ->nullable()
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
                Flex::make([
                    Action::make('create')
                        ->label('Create')
                        ->color('primary')
                        ->action('create'),
                    Action::make('cancel')
                        ->label('Cancel')
                        ->color('gray')
                        ->url(function () {
                            return url()->previous();
                        })
                ])
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function selectProduct($productId): void
    {
        $products = Product::with('unit', 'customerRates')
            ->withOutletStock()
            ->get(['id', 'name', 'selling_price', 'unit_id'])
            ->keyBy('id')
            ->toArray();

        $product = $products[$productId] ?? null;

        if (!$product || $product['current_outlet_stock'] <= 0) {
            Notification::make()
                ->title('Out of Stock')
                ->body('The selected product is out of stock.')
                ->danger()
                ->send();
            return;
        }

        $formState = $this->form->getRawState();
        $items = $formState['items'] ?? [];

        /** ---------------------------------------
         *  1. Add OR increment product
         * ------------------------------------- */
        $found = false;

        foreach ($items as &$item) {
            if (($item['product_id'] ?? null) == $productId) {
                $item['qty'] += 1;
                $found = true;
                if ($item['qty'] >= ($product['current_outlet_stock'] ?? 0)) {
                    $item['qty'] = $product['current_outlet_stock'] ?? 0;

                    Notification::make()
                        ->title('Stock Limit Reached')
                        ->body('You have reached the maximum stock limit for this product.')
                        ->warning()
                        ->send();

                    return;
                }
                break;
            }
        }


        unset($item); // important

        if (!$found) {
            $items[] = [
                'product_id'     => $productId,
                'qty'            => 1,
                'rate'           => $product['selling_price'] ?? 0,
                'discount_type'  => DiscountType::FIXED->value,
                'discount_value' => 0,
                'total'          => 0,
            ];
        }

        /** ---------------------------------------
         *  2. Recalculate line totals
         * ------------------------------------- */
        $total = 0;

        foreach ($items as &$item) {
            $lineTotal = ($item['qty'] ?? 0) * ($item['rate'] ?? 0);

            if (($item['discount_type'] ?? 'fixed') === 'percent') {
                $lineTotal -= ($lineTotal * ($item['discount_value'] ?? 0) / 100);
            } else {
                $lineTotal -= ($item['discount_value'] ?? 0);
            }

            $lineTotal = max($lineTotal, 0);
            $item['total'] = $lineTotal;

            $total += $lineTotal;
        }
        unset($item);

        /** ---------------------------------------
         *  3. Recalculate invoice totals
         * ------------------------------------- */
        $saleDiscountType  = $formState['discount_type'] ?? DiscountType::FIXED->value;
        $saleDiscountValue = $formState['discount_value'] ?? 0;
        $deliveryCharges   = $formState['delivery_charges'] ?? 0;
        $taxCharges        = $formState['tax_charges'] ?? 0;

        $grandTotal = $total;

        if ($saleDiscountType === 'percent') {
            $grandTotal -= ($grandTotal * $saleDiscountValue / 100);
        } else {
            $grandTotal -= $saleDiscountValue;
        }

        $grandTotal += $deliveryCharges + $taxCharges;
        $grandTotal = max($grandTotal, 0);

        /** ---------------------------------------
         *  4. Fill form
         * ------------------------------------- */
        $formState['items']       = $items;
        $formState['total']       = $total;
        $formState['grand_total'] = $grandTotal;

        $this->form->fill($formState);
    }

    public static function calculateTotals(): string
    {
        return <<<'JS'
        const qty  = parseFloat($get('qty')) || 0;
        const rate = parseFloat($get('rate')) || 0;

        const discountType  = $get('discount_type');
        const discountValue = parseFloat($get('discount_value')) || 0;

        let lineTotal = qty * rate;

        if (discountType === 'percent') {
            lineTotal -= (lineTotal * discountValue / 100);
        }

        if (discountType === 'fixed') {
            lineTotal -= discountValue;
        }

        lineTotal = Math.max(lineTotal, 0);

        $set('total', lineTotal);

        // calculate grand total of all items
        const items = $get('../../items') ?? {};
        let grandTotal = Object.values(items).reduce((sum, item) => {
            return sum + (parseFloat(item.total) || 0);
        }, 0);

        // apply invoice-level discount
        const saleDiscountType  = $get('../../discount_type');
        const saleDiscountValue = parseFloat($get('../../discount_value')) || 0;
        const deliveryCharges = parseFloat($get('../../delivery_charges')) || 0;
        const taxCharges = parseFloat($get('../../tax_charges')) || 0;

        let saleGrandTotal = grandTotal;

        if (saleDiscountType === 'percent') {
            saleGrandTotal -= (saleGrandTotal * saleDiscountValue / 100);
        }

        if (saleDiscountType === 'fixed') {
            saleGrandTotal -= saleDiscountValue;
        }

        saleGrandTotal += deliveryCharges;
        // saleGrandTotal += (saleGrandTotal * taxRate / 100);
        saleGrandTotal += taxCharges;

        saleGrandTotal = Math.max(saleGrandTotal, 0);

        // set totals
        $set('../../total', grandTotal);
        $set('../../grand_total', saleGrandTotal);
    JS;
    }

    public static function calculateGrandTotal(): string
    {
        return <<<'JS'
            const saleDiscountType  = $get('discount_type');
            const saleDiscountValue = parseFloat($get('discount_value')) || 0;
            const deliveryCharges = parseFloat($get('delivery_charges')) || 0;
            const taxCharges = parseFloat($get('tax_charges')) || 0;

            const items = $get('items') ?? {};

            let grandTotal = Object.values(items).reduce((sum, item) => {
                return sum + (parseFloat(item.total) || 0);
            }, 0);

            let saleGrandTotal = grandTotal;

            if (saleDiscountType === 'percent') {
                saleGrandTotal -= (saleGrandTotal * saleDiscountValue / 100);
            }

            if (saleDiscountType === 'fixed') {
                saleGrandTotal -= saleDiscountValue;
            }

            saleGrandTotal += deliveryCharges;
            // saleGrandTotal += (saleGrandTotal * taxRate / 100);
            saleGrandTotal += taxCharges;

            saleGrandTotal = Math.max(saleGrandTotal, 0);

            $set('total', grandTotal);
            $set('grand_total', saleGrandTotal);
        JS;
    }
}
