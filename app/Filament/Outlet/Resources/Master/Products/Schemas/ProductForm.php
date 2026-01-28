<?php

namespace App\Filament\Outlet\Resources\Master\Products\Schemas;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use App\Filament\Admin\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Admin\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Admin\Resources\Master\Categories\Schemas\CategoryForm;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('code')
                                    ->suffixAction(Action::make('Generate Code')
                                        ->tooltip('Generate a random product code')
                                        ->iconButton()
                                        ->icon('heroicon-o-arrow-path')
                                        ->actionJs(<<<'JS'
                $set('code',Math.floor(Math.random() * 1000) + 1)
                JS)
                                    )
                                    ->nullable(),
                                TextInput::make('cost_price')
                                    ->required()
                                    ->numeric()
                                    ->default(0.0)
                                    ->minValue(0)
                                    ->prefix(app_currency_symbol()),
                                TextInput::make('selling_price')
                                    ->required()
                                    ->numeric()
                                    ->default(0.0)
                                    ->minValue(0)
                                    ->prefix(app_currency_symbol()),
                                TagsInput::make('tags')
                                    ->placeholder('Tags')
                                    ->columnSpanFull(),
                            ]),
                        Section::make()
                            ->columnSpan(1)
                            ->schema([
                                Select::make('unit_id')
                                    ->relationship('unit', 'name')
                                    ->manageOptionForm(UnitForm::configure($schema)->getComponents())
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->preload()
                                    ->required(),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->manageOptionForm(CategoryForm::configure($schema)->getComponents())
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->preload()
                                    ->required(),
                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->manageOptionForm(BrandForm::configure($schema)->getComponents())
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->preload()
                                    ->required(),
                            ]),
                    ]),
                Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Textarea::make('description')
                                    ->default(null)
                                    ->columnSpanFull(),
                                FileUpload::make('attachments')
                                    ->label('Attachments')
                                    ->multiple()
                                    ->directory('attachments/products')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->deleteUploadedFileUsing(function ($file) {
                                        Storage::disk('public')->delete($file);
                                    })
                                    ->nullable()
                                    ->downloadable()
                                    ->columnSpanFull()
                                    ->openable(),
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
                                FileUpload::make('additional_images')
                                    ->label('Additional Images')
                                    ->multiple()
                                    ->reorderable()
                                      ->removeUploadedFileButtonPosition('right')
                                    ->directory('images/products/additional-images')
                                    ->disk('public')
                                      ->image()
                                       ->imageEditor()
                                    ->visibility('public')
                                    ->deleteUploadedFileUsing(function ($file) {
                                        Storage::disk('public')->delete($file);
                                    })
                                    ->nullable()
                                    ->downloadable()
                                    ->columnSpanFull()
                                    ->openable(),
                            ]),
                    ]),
            ]);
    }
}
