<?php

namespace App\Filament\Admin\Resources\Master\Products\Schemas;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use App\Filament\Admin\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Admin\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Admin\Resources\Master\Categories\Schemas\CategoryForm;

class ProductForm
{
    public static function configure(Schema $schema): Schema
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
                                                    ->unique()
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
                                                            ->manageOptionForm(CategoryForm::configure($schema)->getComponents())
                                                            ->searchable()
                                                            // ->columnSpanFull()
                                                            ->preload()
                                                        // ->required()
                                                        ,
                                                        Select::make('brand_id')
                                                            ->relationship('brand', 'name')
                                                            ->manageOptionForm(BrandForm::configure($schema)->getComponents())
                                                            ->searchable()
                                                            // ->columnSpanFull()
                                                            ->preload()
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
                                            ->manageOptionForm(UnitForm::configure($schema)->getComponents())
                                            ->required()
                                            ->searchable()
                                            ->columnSpanFull()
                                            ->preload()
                                            ->required(),
                                        Select::make('sub_unit_id')
                                            ->relationship('subUnit', 'name')
                                            ->manageOptionForm(UnitForm::configure($schema)->getComponents())
                                            ->nullable()
                                            ->searchable()
                                            // ->columnSpanFull()
                                            ->preload()
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
                                            })
                                            ->minValue(1),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
