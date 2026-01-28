<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'city_id',
        'name',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
