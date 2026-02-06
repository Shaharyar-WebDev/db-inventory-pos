<?php

namespace App\Models\Accounting;

use App\Models\User;
use App\BelongsToOutlet;
use App\Models\Master\Customer;
use App\Models\Scopes\OutletScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLedger extends Model
{
    use BelongsToOutlet;

    protected $fillable = [
        'customer_id',
        'amount',
        'source_id',
        'source_type',
        'transaction_type',
        'remarks',
        'outlet_id'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public static function getBalanceForCustomerId(int $customerId): float
    {
        return CustomerLedger::withoutGlobalScope(OutletScope::class)->where('customer_id', $customerId)
            ->sum('amount');
    }
}
