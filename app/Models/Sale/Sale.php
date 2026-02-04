<?php

namespace App\Models\Sale;

use App\BelongsToOutlet;
use App\Enums\DiscountType;
use App\Models\Sale\SaleItem;
use App\Enums\TransactionType;
use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Accounting\CustomerLedger;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use SoftDeletes, BelongsToOutlet, HasDocumentNumber, ResolvesDocumentNumber;

    public static string $documentNumberColumn = 'sale_number';

    public static string $documentNumberPrefix = 'SALE';

    protected $fillable = [
        'sale_number',
        'customer_id',
        'description',
        'total',
        'discount_type',
        'discount_value',
        'grand_total',
        'outlet_id',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customerLedgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }

    public function ledger()
    {
        return $this->morphOne(CustomerLedger::class, 'source');
    }

    public static function booted()
    {
        static::saved(function ($sale) {
            $total = $sale->items->sum('total');
            $grandTotal = $total;

            if ($sale->discount_type === DiscountType::PERCENT->value) {
                $grandTotal -= ($grandTotal * $sale->discount_value / 100);
            }

            if ($sale->discount_type === DiscountType::FIXED->value) {
                $grandTotal -= $sale->discount_value;
            }

            $sale->total = $total;
            $sale->grand_total = $grandTotal;
            $sale->saveQuietly();

            CustomerLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id'   => $sale->id,
                ],
                [
                    'customer_id'      => $sale->customer_id,
                    'amount'           => $sale->grand_total,
                    'transaction_type' => TransactionType::SALE->value,
                    'remarks'          => 'Sale Saved',
                ]
            );
        });
    }
}
