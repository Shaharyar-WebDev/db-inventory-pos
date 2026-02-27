<?php

namespace App\Models\Sale;

use App\Enums\DiscountType;
use App\Enums\TransactionType;
use App\Models\Accounting\CustomerLedger;
use App\Models\Accounting\ReceiptSale;
use App\Models\Master\Customer;
use App\Models\Sale\SaleItem;
use App\Models\Sale\SaleReturn;
use App\Models\Traits\BelongsToOutlet;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Traits\ResolvesDocumentNumber;
use App\Models\User;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;

class Sale extends Model
{
    use BelongsToOutlet, HasDocumentNumber, ResolvesDocumentNumber;
    use Userstamps;

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

    public function receiptSales()
    {
        return $this->hasMany(ReceiptSale::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class);
    }

    // public function scopeWithMetrics($query)
    // {
    //     return $query->withAggregate('items as cogs', 'SUM(qty * cost)')
    //         ->withAggregate('items as revenue', 'SUM(qty * rate)');
    // }

    // public function getGrossProfitAttribute()
    // {
    //     return ($this->revenue ?? 0) - ($this->cogs ?? 0);
    // }

    // public function getGrossMarginAttribute()
    // {
    //     if (! $this->revenue) {
    //         return 0;
    //     }

    //     return round(($this->gross_profit / $this->revenue) * 100, 2);
    // }

    // public function getNetMarginAttribute()
    // {
    //     if (! $this->revenue) {
    //         return 0;
    //     }
    //     return round(($this->net_profit / $this->revenue) * 100, 2);
    // }

    // public function getNetProfitAttribute()
    // {
    //     return ($this->revenue - $this->discount_amount - $this->cogs) ?? 0;
    // }

    public function getRevenueAttribute()
    {
        $saleRevenue = (float) $this->items()
            ->selectRaw('COALESCE(SUM(qty * rate), 0) as total')
            ->value('total');

        $saleReturnRevenue = 0;

        foreach ($this->saleReturns as $return) {
            foreach ($return->items as $item) {
                $saleReturnRevenue += $item['rate'] * $item['qty'];
            }
        }

        return $saleRevenue - $saleReturnRevenue;
    }

    public function getCogsAttribute()
    {
        $saleCogs = (float) $this->items()
            ->selectRaw('COALESCE(SUM(qty * cost), 0) as total')
            ->value('total');

        $saleReturnCogs = 0;

        foreach ($this->saleReturns as $return) {
            foreach ($return->items as $item) {
                $saleReturnCogs += $item['cost'] * $item['qty'];
            }
        }

        return $saleCogs - $saleReturnCogs;
    }

    public function getGrossProfitAttribute()
    {
        return $this->revenue - $this->cogs;
    }

    public function getGrossMarginAttribute()
    {
        $revenue = $this->revenue;

        if ($revenue == 0) {
            return 0;
        }

        return round(($this->gross_profit / $revenue) * 100, 2);
    }

    public function getTotalDiscountAmountAttribute()
    {
        $saleDiscountAmount = (float) $this->discount_amount;
        $saleReturnDiscount = 0;

        foreach ($this->saleReturns as $return) {
            $saleReturnDiscount += $return['discount_amount'];
        }

        return $saleDiscountAmount - $saleReturnDiscount;
    }

    public function getNetProfitAttribute()
    {
        $totalDiscount = $this->total_discount_amount;
        return $this->revenue - $totalDiscount - $this->cogs;
    }

    public function getNetMarginAttribute()
    {
        $revenue = $this->revenue;

        if ($revenue == 0) {
            return 0;
        }

        return round(($this->net_profit / $revenue) * 100, 2);
    }

    public static function booted()
    {
        // static::addGlobalScope(new WithSaleMetricsScope);

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
