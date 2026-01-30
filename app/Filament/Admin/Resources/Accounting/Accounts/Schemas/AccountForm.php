<?php

namespace App\Filament\Admin\Resources\Accounting\Accounts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('opening_balance')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->hintIcon(
                                'heroicon-m-question-mark-circle',
                                tooltip: 'Enter 0 if there is no opening balance'
                            ),
                        Textarea::make('description')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
