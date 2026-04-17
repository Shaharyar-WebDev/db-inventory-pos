<?php

namespace App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\Schemas;

use App\Filament\Admin\Resources\Accounting\Accounts\Schemas\AccountForm;
use App\Models\Accounting\Account;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class TransferBetweenAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('from_account_id')
                            ->relationship('fromAccount', 'name')
                            ->manageOptionForm(AccountForm::configure(new Schema())->getComponents())
                            ->required(),
                        Select::make('to_account_id')
                            ->relationship('toAccount', 'name')
                            ->manageOptionForm(AccountForm::configure(new Schema())->getComponents())
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->calculator()
                            ->columnSpanFull()
                            ->rules(function (Get $get) {
                                return [
                                    function ($attribute, $value, Closure $fail) use ($get) {
                                        $fromAccountId = $get('from_account_id');

                                        $account = Account::find($fromAccountId);

                                        $availableBalance = $account->ledgers()->sum('amount');

                                        if ($value > $availableBalance) {
                                            $fail("Amount exceed balance at {$account->name}");
                                        }
                                    },
                                ];
                            })
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
