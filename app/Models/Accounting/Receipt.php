<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Models\Master\Customer;
use App\Models\Accounting\Account;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\AccountLedger;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Accounting\CustomerLedger;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use SoftDeletes, BelongsToOutlet, HasDocumentNumber, ResolvesDocumentNumber;

    protected $fillable = [
        'receipt_number',
        'customer_id',
        'account_id',
        'amount',
        'remarks',
        'outlet_id',
    ];

    public static string $documentNumberColumn = 'receipt_number';

    public static string $documentNumberPrefix = 'REC';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function booted()
    {
        static::saved(function ($receipt) {
            // Update account ledger (money coming in, so positive)
            AccountLedger::updateOrCreate(
                [
                    'source_type' => Receipt::class,
                    'source_id' => $receipt->id,
                ],
                [
                    'account_id' => $receipt->account_id,
                    'amount' => $receipt->amount,
                    'transaction_type' => class_basename(Receipt::class),
                    'remarks' => "Payment received from customer '{$receipt->customer->name}'",
                ]
            );

            // Update customer ledger (money received, reduces what customer owes)
            CustomerLedger::updateOrCreate(
                [
                    'source_type' => Receipt::class,
                    'source_id' => $receipt->id,
                ],
                [
                    'customer_id' => $receipt->customer_id,
                    'amount' => -$receipt->amount,
                    'transaction_type' => class_basename(Receipt::class),
                    'remarks' => "Payment received from customer '{$receipt->customer->name}'",
                ]
            );
        });
    }
}
