<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts\Schemas;

use App\Models\Accounting\CustomerLedger;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Group::make()
                            ->columnSpanFull()
                            ->columns(4)
                            ->schema([
                                Select::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->live()
                                    ->partiallyRenderComponentsAfterStateUpdated(['amount'])
                                    ->required(),

                                Select::make('account_id')
                                    ->relationship('account', 'name')
                                    ->live()
                                    ->required(),

                                Select::make('payment_method_id')
                                    ->relationship('paymentMethod', 'name')
                                    ->manageOptionForm([
                                        Section::make()
                                            ->columnSpanFull()
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                // ->required()
                                ,

                                Select::make('rider_id')
                                    ->relationship('rider', 'name')
                                    ->nullable(),
                            ]),
                        TextInput::make('amount')
                            ->columnSpanFull()
                            ->required()
                            ->helperText(function (Get $get) {
                                $customerId = $get('customer_id');
                                if (!$customerId) return null;
                                $balance = CustomerLedger::getBalanceForCustomerId($customerId);

                                return 'Customer balance: ' . currency_format($balance);
                            })
                            ->rules(function (Get $get) {
                                return [
                                    'numeric',
                                    // 'min:0',
                                    function (string $attribute, $value, Closure $fail) use ($get) {
                                        // $customerId = $get('customer_id');
                                        // $accountId = $get('account_id');

                                        // if ($customerId) {
                                        //     $customerBalance = CustomerLedger::getBalanceForCustomerId($customerId);
                                        //     if ($value > $customerBalance) {
                                        //         $fail("Cannot receive more than the customer's current balance (" . currency_format($customerBalance) . ").");
                                        //     }
                                        // }
                                    }
                                ];
                            })
                            ->currency(),

                        Textarea::make('remarks')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
