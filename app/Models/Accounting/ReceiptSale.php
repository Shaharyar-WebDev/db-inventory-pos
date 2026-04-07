<?php

namespace App\Models\Accounting;

use App\Models\Sale\Sale;
use Illuminate\Database\Eloquent\Model;

class ReceiptSale extends Model
{
    protected $fillable = [
        'receipt_id',
        'sale_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public static function booted()
    {
        static::created(function ($receiptSale) {
            $receiptSale->receipt->update([
                'remarks' => $receiptSale->receipt->remarks . ' | Sale : ' . $receiptSale->sale->sale_number,
            ]);
        });
    }
}
