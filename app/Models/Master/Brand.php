<?php

namespace App\Models\Master;

use App\Models\Traits\HasOptions;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    // use SoftDeletes;
    use HasOptions;

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function options()
    {
        return Brand::get()->pluck('name', 'id');
    }
}
