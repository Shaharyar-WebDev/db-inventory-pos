<?php

namespace App\Models\Master;

use App\Enums\CustomerType;
use App\Models\Master\Area;
use App\Models\Master\City;
use App\Enums\TransactionType;
use App\Models\Scopes\OutletScope;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Accounting\CustomerLedger;
use App\Models\Master\CustomerProductRate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city_id',
        'area_id',
        'photo',
        'address',
        'contact',
        'customer_type',
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

    public function productRates(): HasMany
    {
        return $this->hasMany(CustomerProductRate::class);
    }

    // public function scopeWithCustomerBalances($query)
    // {
    //     return $query->withSum('ledgers as current_balance', 'amount');
    // }

    public function scopeWithCustomerBalances($query)
    {
        return $query->withSum([
            'ledgers as current_balance' => function ($q) {
                $q->withoutGlobalScope(OutletScope::class);
            }
        ], 'amount');
    }

    public static function booted()
    {
        static::saved(function ($customer) {
            // if ($customer->opening_balance == 0) {
            //     return;
            // }
            CustomerLedger::withoutGlobalScope(OutletScope::class)->updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'source_type' => Customer::class,
                    'source_id' => $customer->id,
                    'transaction_type' => TransactionType::OPENING_BALANCE->value,
                ],
                [
                    'amount' => $customer->opening_balance ?? 0,
                    'remarks' => 'Opening balance synced',
                    'outlet_id' => null,
                ]
            );
        });

        static::deleting(function ($customer) {
            if ($customer->customer_type === CustomerType::WALK_IN->value) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Walk-in customer cannot be deleted')
                    ->send();

                throw new Halt();
            }
        });
    }
}
