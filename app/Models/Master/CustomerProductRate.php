<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class CustomerProductRate extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'selling_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
