<?php

namespace App\Models\Purchase;

use App\Enums\TransactionType;
use App\Models\Master\Product;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Accounting\SupplierLedger;
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

    public function supplierLedger()
    {
        return $this->morphOne(SupplierLedger::class, 'source');
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
                    'reference_type' => Purchase::class,
                    'reference_id'   => $item->purchase_id,
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
            if ($item->ledger || $item->supplierLedger) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Cannot delete item with linked ledger entries')
                    ->send();

                throw new Halt;
            }
        });
    }
}
