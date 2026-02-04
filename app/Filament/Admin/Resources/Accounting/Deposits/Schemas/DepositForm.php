<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountForm;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('account_id')
                            ->relationship('account', 'name')
                            ->manageOptionForm(AccountForm::configure($schema)->getComponents())
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->currency(),
                    ]),
                Textarea::make('remarks')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
