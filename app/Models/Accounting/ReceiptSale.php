<?php
namespace App\Models\Accounting;

use App\Models\Sale\Sale;
use Illuminate\Database\Eloquent\Model;

class ReceiptSale extends Model
{
    protected $fillable = [
        'receipt_id',
        'sale_id',
        'amount',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
