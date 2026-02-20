<?php
namespace App\Models\Inventory;

use App\Models\Outlet\Outlet;
use App\Models\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class StockTransfer extends Model
{
    use HasDocumentNumber;
    use Userstamps;

    public static string $documentNumberColumn = 'transfer_number';

    public static string $documentNumberPrefix = 'TRANSFER';

    protected $fillable = [
        'transfer_number',
        'from_outlet_id',
        'to_outlet_id',
        'description',
    ];

    public function fromOutlet()
    {
        return $this->belongsTo(Outlet::class, 'from_outlet_id');
    }

    public function toOutlet()
    {
        return $this->belongsTo(Outlet::class, 'to_outlet_id');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }

    public static function booted()
    {
        static::deleting(function ($str) {
            $str->items->each->delete();
        });
    }
}
