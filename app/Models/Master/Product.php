<?php

namespace App\Models\Master;

use App\Models\Master\Unit;
use App\Models\Master\Brand;
use App\Models\Outlet\Outlet;
use Filament\Facades\Filament;
use App\Models\Master\Category;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\InventoryLedger;
use App\Models\Master\CustomerProductRate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'thumbnail',
        'description',
        'additional_images',
        'attachments',
        'unit_id',
        'category_id',
        'brand_id',
        'cost_price',
        'selling_price',
        'tags',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function ledgers()
    {
        return $this->hasMany(InventoryLedger::class);
    }

    public function customerRates(): HasMany
    {
        return $this->hasMany(CustomerProductRate::class);
    }

    public function scopeWithStockCounts($query)
    {
        return $query->withSum('ledgers as current_stock', 'qty');
    }

    public function scopeWithStockValue($query)
    {
        return $query->withSum('ledgers as current_value', 'value');
    }
    public function scopeWithOutletStock($query, $outletId = null)
    {
        $outletId = $outletId ?? Filament::getTenant()?->id;

        return $query->withSum([
            'ledgers as current_outlet_stock' => fn($q) => $q->where('outlet_id', $outletId),
        ], 'qty');
    }

    public function stockByOutlet()
    {
        return $this->hasMany(InventoryLedger::class, 'product_id')
            ->selectRaw('product_id, outlet_id, SUM(qty) as stock')
            ->groupBy('product_id', 'outlet_id')
            ->with('outlet');
    }

    public function valueByOutlet()
    {
        return $this->hasMany(InventoryLedger::class, 'product_id')
            ->selectRaw('product_id, outlet_id, SUM(value) as value')
            ->groupBy('product_id', 'outlet_id')
            ->with('outlet');
    }
}
