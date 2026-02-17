<?php

namespace App\Models\Master;

use App\Models\Inventory\InventoryLedger;
use App\Models\Master\Brand;
use App\Models\Master\Category;
use App\Models\Master\CustomerProductRate;
use App\Models\Master\Unit;
use App\Models\Traits\CalculatesInventoryAvgRate;
use App\Models\Traits\HasOptions;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // use SoftDeletes;
    use CalculatesInventoryAvgRate, HasOptions;

    protected $fillable = [
        'name',
        'code',
        'thumbnail',
        'description',
        'additional_images',
        'attachments',
        'unit_id',
        'sub_unit_id',
        'sub_unit_conversion',
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

    public function subUnit()
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

    public function scopeWithStockAvgRate($query)
    {
        return $query
            ->withSum('ledgers as total_value', 'value')
            ->withSum('ledgers as total_qty', 'qty')
            ->selectRaw('COALESCE(
            (SELECT SUM(value) FROM inventory_ledgers WHERE product_id = products.id)
            /
            NULLIF((SELECT SUM(qty) FROM inventory_ledgers WHERE product_id = products.id), 0)
        , 0) as current_avg_rate');
    }

    public function toBaseQty(float $qty, int $unitId): float
    {
        if ($this->subUnit && $unitId === $this->subUnit->id) {
            $conversion = (float) $this->sub_unit_conversion ?: 1;
            return $qty / $conversion;
        }

        return $qty;
    }

    public function scopeWithOutletStock($query, $outletId = null)
    {
        $outletId = $outletId ?? Filament::getTenant()?->id;

        return $query->withSum([
            'ledgers as current_outlet_stock' => fn($q) => $q->where('outlet_id', $outletId),
        ], 'qty');
    }

    public function scopeWithOutletStockValue($query, $outletId = null)
    {
        $outletId = $outletId ?? Filament::getTenant()?->id;

        return $query->withSum([
            'ledgers as current_outlet_stock_value' => fn($q) => $q->where('outlet_id', $outletId),
        ], 'value');
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

    public function scopeWithTotalSaleQty() {}
}
