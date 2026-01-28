<?php

namespace App\Filament\Admin\Resources\Master\Areas\Schemas;

use App\Filament\Admin\Resources\Master\Cities\Schemas\CityForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AreaForm
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
                        Select::make('city_id')
                            ->required()
                            ->relationship('city', 'name')
                            ->manageOptionForm(CityForm::configure($schema)->getComponents()),
                    ]),
            ]);
    }
}
