<?php
namespace App\Models\Sale;

use App\Enums\TransactionType;
use App\Models\Inventory\InventoryLedger;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SaleReturnItem extends Model
{
    use ResolvesDocumentNumber;

    protected $fillable = [
        'sale_return_id',
        'product_id',
        'unit_id',
        'qty',
        'cost',
        'rate',
        // 'discount_type',
        // 'discount_value',
        'total',
    ];

    public static $parentRelation = 'saleReturn';

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function ledger(): MorphOne
    {
        return $this->morphOne(InventoryLedger::class, 'source');
    }

    public static function booted()
    {
        static::saving(function ($item) {

            // $cost = $item->cost;

            // if (!$item->discount_type) {
            //     $item->discount_type = DiscountType::FIXED;
            //     $item->discount_value = 0;
            // }

            // $item->cost = $item->product->getAvgRateOfUnitAsOf($item->created_at, $item->unit_id)

            $sale = $item->saleReturn->sale;

            $originalSaleItem = $sale->items()
                ->where('product_id', $item->product_id)
                ->first();

            $item->cost = $originalSaleItem?->cost ?? 0;

        });

        static::saved(function ($item) {
            $avgRate = $item->cost;
            $product = $item->product;

            $baseQty = $item->product->toBaseQty($item->qty, $item->unit_id);

            InventoryLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id'   => $item->id,
                ],
                [
                    'product_id'       => $item->product_id,
                    'unit_id'          => $product->unit_id, // base unit
                    'qty'              => $baseQty,
                    'rate'             => $avgRate,
                    'value'            => ($avgRate * $baseQty),
                    'transaction_type' => TransactionType::SALE_RETURN,
                    'remarks'          => 'Sale Return Saved',
                ]
            );
        });

        static::deleting(function ($item) {
            $item->ledger()->delete();
            // if ($item->ledger || $item->supplierLedger) {
            //     Notification::make('record_deletion_error')
            //         ->danger()
            //         ->title('Error While Deleting Record')
            //         ->body('Cannot delete item with linked ledger entries')
            //         ->send();

            //     throw new Halt;
            // }
        });
    }
}
