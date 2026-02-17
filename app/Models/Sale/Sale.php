<?php

namespace App\Models\Sale;

use App\Enums\DiscountType;
use App\Enums\TransactionType;
use App\Models\Accounting\CustomerLedger;
use App\Models\Master\Customer;
use App\Models\Sale\SaleItem;
use App\Models\Sale\SaleReturn;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use \App\Models\Traits\BelongsToOutlet,
        // SoftDeletes,
        \App\Models\Traits\HasDocumentNumber,
        \App\Models\Traits\ResolvesDocumentNumber;

    public static string $documentNumberColumn = 'sale_number';

    public static string $documentNumberPrefix = 'SALE';

    protected $fillable = [
        'sale_number',
        'customer_id',
        'description',
        'total',
        'discount_type',
        'discount_value',
        'discount_amount',
        'delivery_charges',
        'tax_charges',
        'grand_total',
        'outlet_id',
    ];

    protected $casts = [
        'discount_type' => DiscountType::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function ledger()
    {
        return $this->morphOne(CustomerLedger::class, 'source');
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class);
    }

    public static function booted()
    {
        static::saved(function ($sale) {

            // $total = $sale->items()->sum('total');

            // dd($total);

            // $deliveryCharges = $sale->delivery_charges ?? 0;
            // $taxCharges      = $sale->tax_charges ?? 0;

            // $discountAmount = 0;

            // if ($sale->discount_type === DiscountType::PERCENT) {

            //     $discountAmount = ($total * $sale->discount_value) / 100;
            // } elseif ($sale->discount_type === DiscountType::FIXED) {

            //     $discountAmount = $sale->discount_value;
            // }

            // $grandTotal = $total - $discountAmount;

            // $finalGrandTotal = $grandTotal + $deliveryCharges + $taxCharges;

            // if (
            //     $sale->total !== $total ||
            //     $sale->grand_total !== $finalGrandTotal
            // ) {
            //     $sale->updateQuietly([
            //         'total'       => $total,
            //         'grand_total' => $finalGrandTotal,
            //         'discount_amount' => $discountAmount
            //     ]);
            // }

            // if ($total > 0) {
            CustomerLedger::updateOrCreate(
                [
                    'source_type' => self::class,
                    'source_id'   => $sale->id,
                ],
                [
                    'customer_id'      => $sale->customer_id,
                    'amount'           => $sale->grand_total,
                    'transaction_type' => TransactionType::SALE,
                    'remarks'          => 'Sale Saved',
                ]
            );
            // } else {
            // $sale->ledger()->delete();
            // }
        });

        static::deleting(function ($sale) {
            if ($sale->saleReturns()->exists()) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Cannot delete item with linked ledger entries')
                    ->send();

                throw new Exception();
            }
            $sale->ledger()->delete();
            $sale->items->each->delete();
            // if ($sale->ledger) {
            //     Notification::make('record_deletion_error')
            //         ->danger()
            //         ->title('Error While Deleting Record')
            //         ->body('Cannot delete item with linked ledger entries')
            //         ->send();

            //     throw new Exception();
            // }
        });
    }
}
