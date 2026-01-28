<?php

namespace App\Filament\Admin\Resources\Master\Brands\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
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
                        Textarea::make('description')
                            ->nullable()
                            ->default(null),
                    ]),
            ]);
    }
}
