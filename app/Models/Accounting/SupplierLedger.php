<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierLedger extends Model
{
    use BelongsToOutlet;

    protected $fillable = [
        'supplier_id',
        'amount',
        'source_id',
        'source_type',
        'transaction_type',
        'remarks',
        'outlet_id'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public static function getBalanceForSupplierId(int $supplierId): float
    {
        return SupplierLedger::where('supplier_id', $supplierId)
            ->sum('amount');
    }
}
