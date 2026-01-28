<?php

namespace App\Filament\Admin\Resources\Master\Cities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->unique()
                            ->required(),
                    ]),
            ]);
    }
}
