<?php

namespace App\Models\Inventory;

use App\BelongsToOutlet;
use App\Models\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryAdjustment extends Model
{
    use SoftDeletes, BelongsToOutlet, HasDocumentNumber;

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
}
