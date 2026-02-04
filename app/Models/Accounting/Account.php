<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'opening_balance',
    ];

    public function ledgers()
    {
        return $this->hasMany(AccountLedger::class);
    }

    public function scopeWithBalances($query)
    {
        return $query->withSum('ledgers as current_balance', 'amount');
    }

    public static function booted()
    {
        static::saved(function ($account) {
            AccountLedger::updateOrCreate(
                [
                    'source_type' => Account::class,
                    'source_id' => $account->id,
                ],
                [
                    'account_id' => $account->id,
                    'amount' => $account->opening_balance,
                    'transaction_type' => class_basename(Account::class),
                    'remarks' => "Account Created",
                ]
            );
        });
    }
}
