<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Models\Master\Supplier;
use App\Models\Scopes\OutletScope;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\SupplierLedger;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountLedger extends Model
{
    use BelongsToOutlet;

    protected $fillable = [
        'account_id',
        'amount',
        'source_id',
        'source_type',
        'transaction_type',
        'remarks',
        'outlet_id'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public static function getBalanceForAccountId(int $supplierId): float
    {
        return AccountLedger::withoutGlobalScope(OutletScope::class)->where('account_id', $supplierId)
            ->sum('amount');
    }
}
