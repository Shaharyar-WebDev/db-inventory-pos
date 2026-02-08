<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use Illuminate\Support\Str;
use App\Enums\TransactionType;
use App\Models\Master\Supplier;
use App\Models\Purchase\Purchase;
use App\Models\Accounting\Account;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\AccountLedger;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, BelongsToOutlet, HasDocumentNumber, ResolvesDocumentNumber;

    protected $fillable = [
        'payment_number',
        'supplier_id',
        'account_id',
        'purchase_id',
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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public static function booted()
    {
        static::saved(function ($payment) {
            $transactionType = $payment->amount < 0 ? Str::upper(TransactionType::REFUND_OR_ADJUSTMENT->value) : TransactionType::PAYMENT->value;

            AccountLedger::updateOrCreate(
                [
                    'source_type' => Payment::class,
                    'source_id' => $payment->id,
                ],
                [
                    'account_id' => $payment->account_id,
                    'amount' => -$payment->amount,
                    'transaction_type' => $transactionType,
                    'remarks' => $payment->remarks ?? "Payment created for supplier {$payment->supplier->name} from account {$payment->account->name}",
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
                    'transaction_type' => $transactionType,
                    'remarks' =>  $payment->remarks ?? "Payment created for supplier {$payment->supplier->name} from account {$payment->account->name}",
                ]
            );
        });
    }
}
