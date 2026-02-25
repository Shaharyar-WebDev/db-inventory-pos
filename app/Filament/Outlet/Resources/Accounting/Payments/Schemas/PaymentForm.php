<?php

namespace App\Filament\Outlet\Resources\Accounting\Payments\Schemas;

use App\Models\Accounting\AccountLedger;
use App\Models\Accounting\SupplierLedger;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
                        Group::make()
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
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
                                    ->required(),
                            ]),
                        TextInput::make('amount')
                            ->columnSpanFull()
                            ->required()
                            ->helperText(function (Get $get) {
                                $supplierId = $get('supplier_id');
                                if (!$supplierId) return;
                                $balance = SupplierLedger::getBalanceForSupplierId($supplierId);

                                return 'Supplier balance: ' . currency_format($balance);
                            })
                            ->rules(fn(Get $get, ?Model $record) => [
                                'min:0',
                                function (string $attribute, $value, Closure $fail) use ($get, $record) {
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
                                        if ($record) {
                                            $accountBalance += $record->amount ?? 0;
                                        }
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
                        FileUpload::make('attachments')
                            ->label('Attachments')
                            ->multiple()
                            ->directory('attachments/payment')
                            ->disk('public')
                            ->visibility('public')
                            ->deleteUploadedFileUsing(function ($file) {
                                Storage::disk('public')->delete($file);
                            })
                            ->nullable()
                            ->downloadable()
                            ->columnSpanFull()
                            ->openable(),
                    ]),
            ]);
    }
}
