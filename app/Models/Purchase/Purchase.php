<?php

namespace App\Models\Purchase;

use App\BelongsToOutlet;
use App\Models\Master\Supplier;
use App\Models\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use BelongsToOutlet, HasDocumentNumber, SoftDeletes;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'description',
        'outlet_id',
        'grand_total'
    ];

    public static string $documentNumberColumn = 'purchase_number';

    public static string $documentNumberPrefix = 'PO';

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function booted(){
        static::saved(function($purchase){
            $total = $purchase->items
            ->sum(fn ($item) => ($item['qty'] ?? 0) * ($item['rate'] ?? 0));
            $purchase->grand_total = $total;
            $purchase->saveQuietly();
        });
    }
}
