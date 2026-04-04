<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'symbol',
    ];

    public static function options()
    {
        return Unit::get()->pluck('name', 'id');
    }
}
