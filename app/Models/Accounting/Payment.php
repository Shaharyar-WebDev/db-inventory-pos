<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Models\Master\Supplier;
use App\Models\Accounting\Account;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\AccountLedger;
use App\Models\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, BelongsToOutlet, HasDocumentNumber;

    protected $fillable = [
        'payment_number',
        'supplier_id',
        'account_id',
        'amount',
        'remarks',
        'outlet_id',
    ];

    public static string $documentNumberColumn = 'payment_number';
    public static string $documentNumberPrefix = 'PAY';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function booted()
    {
        static::saved(function ($payment) {
            AccountLedger::updateOrCreate(
                [
                    'source_type' => Payment::class,
                    'source_id' => $payment->id,
                ],
                [
                    'account_id' => $payment->account_id,
                    'amount' => -$payment->amount,
                    'transaction_type' => class_basename(Payment::class),
                    'remarks' => "Payment created for supplier {$payment->supplier->name}",
                ]
            );
            SupplierLedger::updateOrCreate(
                [
                    'source_type' => Payment::class,
                    'source_id' => $payment->id,
                ],
                [
                    'supplier_id' => $payment->supplier_id,
                    'amount' => -$payment->amount,
                    'transaction_type' => class_basename(Payment::class),
                    'remarks' => "Payment created for supplier {$payment->supplier->name}",
                ]
            );
        });
    }
}
