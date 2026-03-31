<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Schemas;

use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountForm;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

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
                            ->calculator()
                            ->currency(),
                    ]),
                Textarea::make('remarks')
                    ->nullable()
                    ->columnSpanFull(),
                FileUpload::make('attachments')
                    ->label('Attachments')
                    ->multiple()
                    ->directory('attachments/deposits')
                    ->disk('public')
                    ->visibility('public')
                    ->deleteUploadedFileUsing(function ($file) {
                        Storage::disk('public')->delete($file);
                    })
                    ->nullable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->openable(),
            ]);
    }
}
