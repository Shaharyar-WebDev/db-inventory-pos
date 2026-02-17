<?php

namespace App\Models\Purchase;

use App\Enums\TransactionType;
use App\Models\Master\Product;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Inventory\InventoryLedger;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturnItem extends Model
{
    use ResolvesDocumentNumber;
    
    protected $fillable = [
        'purchase_return_id',
        'product_id',
        'qty',
        'rate',
        'total',
    ];

    public static $parentRelation = 'purchaseReturn';

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ledger()
    {
        return $this->morphOne(InventoryLedger::class, 'source');
    }

    public static function booted()
    {
        static::saved(function ($item) {
            InventoryLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id' => $item->id,
                ],
                [
                    'product_id' => $item->product_id,
                    'unit_id' => $item->product?->unit_id,
                    'qty' => -$item->qty,
                    'rate' => $item->rate,
                    'value' => -$item->total,
                    'transaction_type' => TransactionType::PURCHASE_RETURN->value,
                    'remarks' => 'Purchase Return Saved',
                ]
            );
        });

        static::saving(function ($item) {
            $value = $item->qty * $item->rate;
            $item->total = $value;
            $item->saveQuietly();
        });

        static::deleting(function ($item) {
            $item->ledger()->delete();
            // if ($item->ledger) {
            //     Notification::make('record_deletion_error')
            //         ->danger()
            //         ->title('Error While Deleting Record')
            //         ->body('Cannot delete item with linked ledger entries')
            //         ->send();

            //     throw new Halt();
            // }
        });
    }
}
