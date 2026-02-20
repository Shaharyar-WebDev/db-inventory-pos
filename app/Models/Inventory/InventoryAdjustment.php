<?php
namespace App\Models\Inventory;

use App\BelongsToOutlet;
use App\Models\Inventory\InventoryAdjustmentItem;
use App\Models\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class InventoryAdjustment extends Model
{
    use BelongsToOutlet, HasDocumentNumber;
    use Userstamps;

    protected $fillable = [
        'adjustment_number',
        'date',
        'description',
    ];

    public static string $documentNumberColumn = 'adjustment_number';

    public static string $documentNumberPrefix = 'ADJ';

    public function items()
    {
        return $this->hasMany(InventoryAdjustmentItem::class);
    }

    public static function booted()
    {
        static::deleting(function ($adjustment) {
            $adjustment->items->each->delete();
        });
    }
}
