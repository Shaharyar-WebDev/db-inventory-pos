<?php

namespace App\Models\Sale;

use App\Models\Sale\Sale;
use App\Enums\DiscountType;
use App\Enums\TransactionType;
use App\Models\Master\Product;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Accounting\SupplierLedger;
use App\Models\Inventory\InventoryLedger;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use ResolvesDocumentNumber;

    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',
        'rate',
        'discount_type',
        'discount_value',
        'total'
    ];

    public static $parentRelation = 'sale';

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function booted()
    {
        static::saving(function ($item) {
            $qty   = $item->qty;
            $rate  = $item->rate;

            $total = $qty * $rate;

            if ($item->discount_type === DiscountType::PERCENT->value) {
                $total -= ($total * $item->discount_value / 100);
            }

            if ($item->discount_type === DiscountType::FIXED->value) {
                $total -= $item->discount_value;
            }

            $item->total =  $total;
        });

        static::saved(function ($item) {
            InventoryLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id'   => $item->id,
                ],
                [
                    'reference_type' => Sale::class,
                    'reference_id'   => $item->sale_id,
                    'product_id'       => $item->product_id,
                    'unit_id'          => $item->product->unit_id,
                    'qty'              => -$item->qty,
                    'rate'             => $item->rate,
                    'value'            => -$item->total,
                    'transaction_type' => TransactionType::SALE->value,
                    'remarks'          => 'Sale Saved',
                ]
            );
        });
    }
}
