<?php

namespace App\Models\Accounting;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\AccountLedger;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use
        //  SoftDeletes
        HasDocumentNumber,
        ResolvesDocumentNumber;

    protected $fillable = [
        'deposit_number',
        'account_id',
        'amount',
        'remarks',
    ];

    public static string $documentNumberColumn = 'deposit_number';

    public static string $documentNumberPrefix = 'DEP';

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function booted()
    {
        static::saved(function ($deposit) {
            AccountLedger::updateOrCreate([
                'source_id' => $deposit->id,
                'source_type' => Deposit::class,
            ], [
                'account_id' => $deposit->account_id,
                'amount' => $deposit->amount,
                'transaction_type' => TransactionType::DEPOSIT->value,
                'remarks' => 'Deposit created',
                'outlet_id' => null
            ]);
        });
    }
}
