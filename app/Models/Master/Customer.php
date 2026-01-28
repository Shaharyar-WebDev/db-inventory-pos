<?php

namespace App\Models\Master;

use App\Enums\TransactionType;
use App\Models\Accounting\CustomerLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city_id',
        'area_id',
        'address',
        'contact',
        'opening_balance',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function ledgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }

    public function scopeWithCustomerBalances($query)
    {
        return $query->withSum('ledgers as current_balance', 'amount');
    }


    public static function booted()
    {
        static::created(function ($customer) {
            self::syncOpeningBalance($customer);
        });

        static::updated(function ($customer) {
            self::syncOpeningBalance($customer);
        });
    }

    private static function syncOpeningBalance(Customer $customer)
    {
        if ($customer->opening_balance == 0) {
            return;
        }

        CustomerLedger::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'source_type' => Customer::class,
                'source_id' => $customer->id,
                'transaction_type' => TransactionType::OPENING_BALANCE->value,
            ],
            [
                'amount' => $customer->opening_balance,
                'remarks' => 'Opening balance synced',
                'outlet_id' => null,
            ]
        );
    }
}
