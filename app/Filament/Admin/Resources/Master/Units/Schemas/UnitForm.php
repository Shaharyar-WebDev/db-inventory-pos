<?php

namespace App\Filament\Admin\Resources\Master\Units\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class UnitForm
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
                        TextInput::make('symbol')
                            ->unique()
                            ->required(),
                    ]),
            ]);
    }
}
