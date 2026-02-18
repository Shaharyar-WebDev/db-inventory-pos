<?php
namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Enums\TransactionType;
use App\Models\Accounting\Account;
use App\Models\Accounting\AccountLedger;
use App\Models\Accounting\CustomerLedger;
use App\Models\Accounting\PaymentMethod;
use App\Models\Master\Customer;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Traits\ResolvesDocumentNumber;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use BelongsToOutlet, HasDocumentNumber, ResolvesDocumentNumber;

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

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function accountLedger()
    {
        return $this->morphOne(AccountLedger::class, 'source');
    }

    public function customerLedger()
    {
        return $this->morphOne(CustomerLedger::class, 'source');
    }

    public static function booted()
    {
        static::saved(function ($receipt) {
            $transactionType = $receipt->amount < 0 ? TransactionType::RECEIPT_REFUND_OR_ADJUSTMENT : TransactionType::RECEIPT;

            AccountLedger::updateOrCreate(
                [
                    'source_type' => Receipt::class,
                    'source_id'   => $receipt->id,
                ],
                [
                    'account_id'       => $receipt->account_id,
                    'amount'           => $receipt->amount,
                    'transaction_type' => $transactionType,
                    'remarks'          => "Payment received from customer '{$receipt->customer->name}'",
                ]
            );

            // Update customer ledger (money received, reduces what customer owes)
            CustomerLedger::updateOrCreate(
                [
                    'source_type' => Receipt::class,
                    'source_id'   => $receipt->id,
                ],
                [
                    'customer_id'      => $receipt->customer_id,
                    'amount'           => -$receipt->amount,
                    'transaction_type' => $transactionType,
                    'remarks'          => "Payment received from customer '{$receipt->customer->name}'",
                ]
            );
        });

        static::deleting(function ($receipt) {
            $receipt->accountLedger()->delete();
            $receipt->customerLedger()->delete();
        });
    }
}
