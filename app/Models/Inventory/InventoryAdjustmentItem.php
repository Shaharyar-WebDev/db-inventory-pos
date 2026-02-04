<?php

namespace App\Models\Inventory;

use Exception;
use App\Enums\TransactionType;
use App\Models\Master\Product;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Traits\ResolvesDocumentNumber;

class InventoryAdjustmentItem extends Model
{
    use ResolvesDocumentNumber;

    protected $fillable = [
        'inventory_adjustment_id',
        'product_id',
        'qty',
    ];

    public static $parentRelation = 'adjustment';

    public function adjustment()
    {
        return $this->belongsTo(InventoryAdjustment::class, 'inventory_adjustment_id');
    }

    public function product()
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
            $value = $item->qty * $item->product->cost_price;

            InventoryLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id'   => $item->id,
                ],
                [
                    'reference_type' => InventoryAdjustment::class,
                    'reference_id'   =>  $item->inventory_adjustment_id,
                    'product_id'       => $item->product_id,
                    'unit_id'          => $item->product->unit_id,
                    'qty'              => $item->qty,
                    'rate'             => $item->product->cost_price,
                    'value'            => $value,
                    'transaction_type' => TransactionType::INVENTORY_ADJUSTMENT->value,
                    'remarks'          => 'Inventory Adjustment Saved',
                ]
            );
        });

        static::deleting(function ($item) {
            if ($item->ledger) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Cannot remove linked record')
                    ->send();
                throw new Halt();
            }
        });
    }
}
