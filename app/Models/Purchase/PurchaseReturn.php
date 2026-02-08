<?php

namespace App\Models\Purchase;

use App\BelongsToOutlet;
use App\Models\Purchase\Purchase;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasDocumentNumber;
use Filament\Notifications\Notification;
use App\Models\Accounting\SupplierLedger;
use App\Models\Purchase\PurchaseReturnItem;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturn extends Model
{
    use BelongsToOutlet, HasDocumentNumber, SoftDeletes, ResolvesDocumentNumber;

    protected $fillable = [
        'return_number',
        'purchase_id',
        'description',
        'outlet_id',
        'grand_total'
    ];

    public static string $documentNumberColumn = 'return_number';

    public static string $documentNumberPrefix = 'PR';

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public static function booted()
    {
        static::saved(function ($return) {
            $total = $return->items->sum('total');
            $return->grand_total = $total;
            $return->saveQuietly();

            SupplierLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id' => $return->id,
                ],
                [
                    'supplier_id' => $return->purchase->supplier_id,
                    'amount' => -$return->grand_total,
                    'transaction_type' => class_basename(self::class),
                    'remarks' => 'Purchase Return Saved',
                ]
            );
        });

        static::deleting(function ($item) {
            if ($item->ledger || $item->supplierLedger) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Cannot delete item with linked ledger entries')
                    ->send();

                throw new Halt();
            }
        });
    }
}
