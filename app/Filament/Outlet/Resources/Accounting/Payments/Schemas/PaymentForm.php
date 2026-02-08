<?php

namespace App\Filament\Outlet\Resources\Accounting\Payments\Schemas;

use Closure;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use App\Models\Accounting\AccountLedger;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\Accounting\SupplierLedger;
use Filament\Schemas\Components\Utilities\Get;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        // Group::make()
                        //     ->columnSpanFull()
                        //     ->columns(3)
                        //     ->schema([
                        Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->live()
                            ->partiallyRenderComponentsAfterStateUpdated(['amount'])
                            ->required(),
                        Select::make('account_id')
                            ->relationship('account', 'name')
                            ->live()
                            ->partiallyRenderComponentsAfterStateUpdated(['amount'])
                            ->required(),
                        // Select::make('purchase_id')
                        //     ->relationship('purchase', 'purchase_number')
                        //     ->disabled()
                        //     ->live(),
                        // ]),
                        TextInput::make('amount')
                            ->columnSpanFull()
                            ->required()
                            ->helperText(function (Get $get) {
                                $supplierId = $get('supplier_id');
                                if (!$supplierId) return;
                                $balance = SupplierLedger::getBalanceForSupplierId($supplierId);

                                return 'Supplier balance: ' . currency_format($balance);
                            })
                            ->rules(fn(Get $get) => [
                                // 'min:0',
                                function (string $attribute, $value, Closure $fail) use ($get) {
                                    // $supplierId = $get('supplier_id');
                                    $accountId = $get('account_id');

                                    // if ($supplierId) {
                                    //     $supplierBalance = SupplierLedger::getBalanceForSupplierId($supplierId);
                                    //     if ($value > abs($supplierBalance)) {
                                    //         $fail("Cannot pay more than the supplier's current balance (" . app_currency_symbol() . currency_format($supplierBalance) . ").");
                                    //     }
                                    // }

                                    if ($accountId) {
                                        $accountBalance = AccountLedger::getBalanceForAccountId($accountId);
                                        if ($value > $accountBalance) {
                                            $fail("Insufficient account balance to make this payment.");
                                        }
                                    }
                                }
                            ])
                            ->currency(),
                        Textarea::make('remarks')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
