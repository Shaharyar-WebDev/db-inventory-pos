<?php

namespace App\Models\Inventory;

use App\Enums\TransactionType;
use App\Models\Master\Product;
use App\Models\Traits\ResolvesDocumentNumber;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class StockTransferItem extends Model
{
    use ResolvesDocumentNumber;

    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'qty',
    ];

    public static $parentRelation = 'stockTransfer';

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function booted()
    {
        static::saved(function ($item) {
            $transfer = $item->stockTransfer;
            $fromOutletId = $transfer->from_outlet_id;
            $toOutletId   = $transfer->to_outlet_id;

            $avgRate = InventoryLedger::where('product_id', $item->product_id)
                ->where('outlet_id', $fromOutletId)
                ->lockForUpdate()
                ->selectRaw('
                    CASE
                        WHEN SUM(qty) = 0 THEN 0
                        ELSE SUM(qty * rate) / SUM(qty)
                    END as avg_rate
                ')
                ->value('avg_rate');

            $value = $item->qty * $avgRate;

            InventoryLedger::updateOrCreate(
                [
                    'source_type' => StockTransferItem::class,
                    'source_id'   => $item->id,
                    'transaction_type' => TransactionType::STOCK_TRANSFER_OUT->value,
                ],
                [
                    'reference_type' => StockTransfer::class,
                    'reference_id'   => $item->stock_transfer_id,
                    'outlet_id'   => $fromOutletId,
                    'product_id'  => $item->product_id,
                    'unit_id'          => $item->product->unit_id,
                    'qty'              => -$item->qty,
                    'rate'             => $avgRate,
                    'value'            => -$value,
                    'remarks'          => 'Stock transferred out to outlet ' . $transfer->toOutlet->name,
                ]
            );

            // IN row
            InventoryLedger::updateOrCreate(
                [
                    'source_type' => StockTransferItem::class,
                    'source_id'   => $item->id,
                    'transaction_type' =>  TransactionType::STOCK_TRANSFER_IN->value,
                ],
                [
                    'reference_type' => StockTransfer::class,
                    'reference_id'   => $item->stock_transfer_id,
                    'outlet_id'   => $toOutletId,
                    'product_id'  => $item->product_id,
                    'unit_id'          => $item->product->unit_id,
                    'qty'              => $item->qty,
                    'rate'             => $avgRate,
                    'value'            => $value,
                    'remarks'          => 'Stock transferred in from outlet ' . $transfer->fromOutlet->name,
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
