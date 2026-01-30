<?php

namespace App\Models\Inventory;

use App\BelongsToOutlet;
use App\Models\Master\Unit;
use App\Models\Master\Product;
use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    use BelongsToOutlet;

    protected $fillable = [
        'product_id',
        'unit_id',
        'qty',
        'rate',
        'value',
        'source_id',
        'source_type',
        'reference_id',
        'reference_type',
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
}
