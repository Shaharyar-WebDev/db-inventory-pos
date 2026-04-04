<?php

namespace App\Filament\Admin\Resources\Master\Products\RelationManagers;

use App\Filament\Admin\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Admin\Resources\Master\Categories\Schemas\CategoryForm;
use App\Filament\Admin\Resources\Master\Units\Schemas\UnitForm;
use App\Models\Master\Brand;
use App\Models\Master\Category;
use App\Models\Master\Unit;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->contained(false)
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                Group::make()
                                    ->columnSpanFull()
                                    ->columns(3)
                                    ->schema([
                                        Section::make()
                                            ->columnSpan(2)
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    // ->unique()
                                                    ->columnSpanFull()
                                                    ->required(),
                                                // TextInput::make('code')
                                                //     ->unique()
                                                //     ->suffixAction(Action::make('Generate Code')
                                                //         ->tooltip('Generate a random product code')
                                                //         ->iconButton()
                                                //         ->icon('heroicon-o-arrow-path')
                                                //         ->actionJs(<<<'JS'
                                                //             $set('code',Math.floor(Math.random() * 1000) + 1)
                                                //         JS)
                                                //     )
                                                //     ->nullable(),
                                                TextInput::make('cost_price')
                                                    ->required()
                                                    ->numeric()
                                                    ->currency()
                                                    ->minValue(0),
                                                TextInput::make('selling_price')
                                                    ->required()
                                                    ->numeric()
                                                    ->currency()
                                                    ->minValue(0),
                                                // TagsInput::make('tags')
                                                //     ->placeholder('Tags')
                                                //     ->columnSpanFull(),
                                                Group::make()
                                                    ->columnSpanFull()
                                                    ->columns(2)
                                                    ->schema([
                                                        Select::make('category_id')
                                                            ->relationship('category', 'name')
                                                            ->options(Category::options())
                                                            ->manageOptionForm(CategoryForm::configure($schema)->getComponents())
                                                            ->searchable()
                                                            // ->columnSpanFull()
                                                            ->preload(false)
                                                        // ->required()
                                                        ,
                                                        Select::make('brand_id')
                                                            ->relationship('brand', 'name')
                                                            ->options(Brand::options())
                                                            ->manageOptionForm(BrandForm::configure($schema)->getComponents())
                                                            ->searchable()
                                                            // ->columnSpanFull()
                                                            ->preload(false)
                                                        // ->required()
                                                        ,
                                                    ])
                                            ]),
                                        Section::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                FileUpload::make('thumbnail')
                                                    ->label('Thumbnail Image')
                                                    ->directory('images/products/thumbnails')
                                                    ->disk('public')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->visibility('public')
                                                    ->deleteUploadedFileUsing(function ($file) {
                                                        Storage::disk('public')->delete($file);
                                                    })
                                                    ->nullable()
                                                    ->removeUploadedFileButtonPosition('right')
                                                    ->downloadable()
                                                    ->columnSpanFull()
                                                    ->openable(),
                                            ]),
                                    ]),
                                Group::make()
                                    ->columnSpanFull()
                                    ->columns(3)
                                    ->schema([
                                        Section::make()
                                            ->columnSpanFull()
                                            ->schema([
                                                Textarea::make('description')
                                                    ->default(null)
                                                    ->columnSpanFull(),
                                                // FileUpload::make('attachments')
                                                //     ->label('Attachments')
                                                //     ->multiple()
                                                //     ->directory('attachments/products')
                                                //     ->disk('public')
                                                //     ->visibility('public')
                                                //     ->deleteUploadedFileUsing(function ($file) {
                                                //         Storage::disk('public')->delete($file);
                                                //     })
                                                //     ->nullable()
                                                //     ->downloadable()
                                                //     ->columnSpanFull()
                                                //     ->openable(),
                                            ]),
                                        // Section::make()
                                        //     ->columnSpan(1)
                                        //     ->schema([
                                        //     FileUpload::make('additional_images')
                                        //         ->label('Additional Images')
                                        //         ->multiple()
                                        //         ->reorderable()
                                        //         ->removeUploadedFileButtonPosition('right')
                                        //         ->directory('images/products/additional-images')
                                        //         ->disk('public')
                                        //         ->image()
                                        //         ->imageEditor()
                                        //         ->visibility('public')
                                        //         ->deleteUploadedFileUsing(function ($file) {
                                        //             Storage::disk('public')->delete($file);
                                        //         })
                                        //         ->nullable()
                                        //         ->downloadable()
                                        //         ->columnSpanFull()
                                        //         ->openable(),
                                    ]),
                            ]),
                        Tab::make('Unit')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('unit_id')
                                            ->relationship('unit', 'name')
                                            ->options(Unit::options())
                                            ->manageOptionForm(UnitForm::configure($schema)->getComponents())
                                            ->required()
                                            ->searchable()
                                            ->columnSpanFull()
                                            ->preload(false)
                                            ->required(),
                                        Select::make('sub_unit_id')
                                            ->relationship('subUnit', 'name')
                                            ->options(Unit::options())
                                            ->manageOptionForm(UnitForm::configure($schema)->getComponents())
                                            ->nullable()
                                            ->searchable()
                                            // ->columnSpanFull()
                                            ->preload(false)
                                        // ->required()
                                        ,
                                        TextInput::make('sub_unit_conversion')
                                            ->numeric()
                                            ->step(1)
                                            ->required(function (Get $get) {
                                                $subUnitId = $get('sub_unit_id');

                                                if (filled($subUnitId)) {
                                                    return true;
                                                }
                                                return false;
                                            })
                                            ->minValue(function (Get $get) {
                                                $subUnitId = $get('sub_unit_id');

                                                if (filled($subUnitId)) {
                                                    return 1;
                                                }
                                                return null;
                                            })
                                            ->default(0)
                                            ->nullable(false),

                                        TextInput::make('sub_unit_selling_price')
                                            ->numeric()
                                            ->step(1)
                                            ->required(function (Get $get) {
                                                $subUnitId = $get('sub_unit_id');

                                                if (filled($subUnitId)) {
                                                    return true;
                                                }
                                                return false;
                                            })
                                            ->minValue(function (Get $get) {
                                                $subUnitId = $get('sub_unit_id');

                                                if (filled($subUnitId)) {
                                                    return 1;
                                                }
                                                return null;
                                            })
                                            ->default(0)
                                            ->nullable(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->imageSize(80)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                // TextColumn::make('code')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->copyable(),
                TextColumn::make('category.name')
                    ->copyable(),
                TextColumn::make('brand.name')
                    ->copyable(),
                TextColumn::make('unit.name')
                    ->copyable(),
                TextColumn::make('group.name')
                    ->copyable(),
                TextColumn::make('cost_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('selling_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->slideOver()->modalWidth(Width::FiveExtraLarge),
                AssociateAction::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make()->slideOver()->modalWidth(Width::FiveExtraLarge),
                DissociateAction::make(),
                DeleteAction::make(),
                ReplicateAction::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->rules('unique:products,name')
                        ->default(fn(Get $get) => $get('name') . ' Copy'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
