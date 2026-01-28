<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\SupplierLedger;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact',
        'address',
    ];

    public function ledgers()
    {
        return $this->hasMany(SupplierLedger::class);
    }

    public function scopeWithBalances($query)
    {
        return $query->withSum('ledgers as current_balance', 'amount');
    }
}
