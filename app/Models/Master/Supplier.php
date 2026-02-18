<?php
namespace App\Models\Master;

use App\Enums\TransactionType;
use App\Models\Accounting\SupplierLedger;
use App\Models\Scopes\OutletScope;
use App\Models\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasStatus;

    protected $fillable = [
        'name',
        'contact',
        'address',
        'opening_balance',
    ];

    public function ledgers()
    {
        return $this->hasMany(SupplierLedger::class);
    }

    public function scopeWithBalances($query)
    {
        return $query->withSum('ledgers as current_balance', 'amount');
    }

    public function getCurrentBalance()
    {
        return SupplierLedger::getBalanceForSupplierId($this->id);
    }

    public function getSupplierBalanceAsOf($asOf = null): float
    {
        $asOf = $asOf ? Carbon::parse($asOf) : now();

        return SupplierLedger::getSupplierBalanceQuery($this->id)
            ->where('created_at', '<', $asOf)
            ->sum('amount');
    }

    public static function booted()
    {
        static::saved(function ($supplier) {
            // if ($supplier->opening_balance == 0) {
            //     return;
            // }
            SupplierLedger::withoutGlobalScope(OutletScope::class)->updateOrCreate(
                [
                    'supplier_id'      => $supplier->id,
                    'source_type'      => self::class,
                    'source_id'        => $supplier->id,
                    'transaction_type' => TransactionType::OPENING_BALANCE->value,
                ],
                [
                    'amount'    => $supplier->opening_balance,
                    'remarks'   => 'Opening balance synced',
                    'outlet_id' => null,
                ]
            );
        });
    }
}
