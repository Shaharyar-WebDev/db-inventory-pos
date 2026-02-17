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

class PurchaseItem extends Model
{
    use ResolvesDocumentNumber;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'qty',
        'rate',
        'total'
    ];

    public static $parentRelation = 'purchase';

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
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
                    'qty' => $item->qty,
                    'rate' => $item->rate,
                    'value' => $item->total,
                    'transaction_type' => TransactionType::PURCHASE->value,
                    'remarks' => 'Purchase Saved',
                ]
            );
        });

        static::saving(function ($item) {
            $value = $item->qty * $item->rate;
            $item->total = $value;
            $item->saveQuietly();
        });

        static::deleting(function ($item) {
            if ($item->purchase->purchaseReturns()->exists()) {
                Notification::make()
                    ->title('Cannot Delete')
                    ->body('This record cannot be deleted because the purchase has been returned.')
                    ->danger()
                    ->send();

                throw new Halt("This record cannot be deleted because the purchase has been returned.");
            }

            $item->ledger()->delete();
        });
    }
}
