<?php

namespace App\Models\Purchase;

use App\BelongsToOutlet;
use App\Enums\TransactionType;
use App\Models\Master\Supplier;
use App\Models\Accounting\Payment;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasDocumentNumber;
use Filament\Notifications\Notification;
use App\Models\Accounting\SupplierLedger;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use BelongsToOutlet, HasDocumentNumber, SoftDeletes, ResolvesDocumentNumber;

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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public static function booted()
    {
        static::saved(function ($purchase) {

            $total = $purchase->items->sum('total');
            $purchase->grand_total = $total;
            $purchase->saveQuietly();

            // Supplier Ledger
            SupplierLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id' => $purchase->id,
                ],
                [
                    'supplier_id' => $purchase->supplier_id,
                    'amount' => $purchase->grand_total,
                    'transaction_type' => class_basename(Purchase::class),
                    'remarks' => 'Purchase Saved',
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
