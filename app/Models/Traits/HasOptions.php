<?php

namespace App\Models\Traits;

trait HasOptions
{
    public static function options()
    {
        return self::get()->pluck('name', 'id');
    }
}
