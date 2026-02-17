<?php

namespace App\Models\Inventory;

use App\BelongsToOutlet;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use App\Models\Traits\HasTransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    use BelongsToOutlet, HasTransactionType;

    protected $fillable = [
        'product_id',
        'unit_id',
        'qty',
        'rate',
        'value',
        'source_id',
        'source_type',
        'transaction_type',
        'remarks',
        'outlet_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public static function getAvgRateQueryForProduct(int $productId)
    {
        return InventoryLedger::where('product_id', $productId)
            ->selectRaw('SUM(value) / NULLIF(SUM(qty), 0) as avg_rate');
    }
}
