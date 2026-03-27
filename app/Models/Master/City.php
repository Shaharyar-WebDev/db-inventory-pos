<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public static function options()
    {
        return City::get()->pluck('name', 'id');
    }
}
