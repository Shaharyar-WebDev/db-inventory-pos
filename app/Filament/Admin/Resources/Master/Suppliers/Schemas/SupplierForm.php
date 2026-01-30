<?php

namespace App\Filament\Admin\Resources\Master\Suppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->unique()
                            ->required(),
                        TextInput::make('contact')
                            ->nullable()
                            ->tel()
                            ->default(null),
                        TextInput::make('opening_balance')
                            ->numeric()
                            ->columnSpanFull()
                            ->default(0)
                            ->required()
                            ->hintIcon(
                                'heroicon-m-question-mark-circle',
                                tooltip: 'Enter 0 if there is no opening balance'
                            ),
                        Textarea::make('address')
                            ->nullable()
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
