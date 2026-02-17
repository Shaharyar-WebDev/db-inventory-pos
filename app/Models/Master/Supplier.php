<?php

namespace App\Models\Master;

use App\Enums\TransactionType;
use App\Models\Accounting\SupplierLedger;
use App\Models\Scopes\OutletScope;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    // use SoftDeletes;
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

    public static function booted()
    {
        static::saved(function ($supplier) {
            // if ($supplier->opening_balance == 0) {
            //     return;
            // }
            SupplierLedger::withoutGlobalScope(OutletScope::class)->updateOrCreate(
                [
                    'supplier_id' => $supplier->id,
                    'source_type' => self::class,
                    'source_id' => $supplier->id,
                    'transaction_type' => TransactionType::OPENING_BALANCE->value,
                ],
                [
                    'amount' => $supplier->opening_balance,
                    'remarks' => 'Opening balance synced',
                    'outlet_id' => null,
                ]
            );
        });
    }
}
