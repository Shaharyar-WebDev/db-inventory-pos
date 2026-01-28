<?php

namespace App\Models\Traits;

trait HasDocumentNumber
{
    protected static function bootHasDocumentNumber()
    {
        static::creating(function ($model) {
            if (! isset(static::$documentNumberColumn, static::$documentNumberPrefix)) {
                return;
            }

            $column = static::$documentNumberColumn;

            if (! $model->{$column}) {
                $model->{$column} = generateDocumentNumber(
                    static::$documentNumberPrefix,
                    static::class
                );
            }
        });
    }
}
