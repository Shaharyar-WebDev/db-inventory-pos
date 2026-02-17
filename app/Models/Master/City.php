<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
