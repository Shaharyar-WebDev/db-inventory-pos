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
}
