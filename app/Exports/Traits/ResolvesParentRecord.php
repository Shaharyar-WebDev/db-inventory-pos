<?php

namespace App\Exports\Traits;

trait ResolvesParentRecord
{
    private function resolveParentRecord($source): mixed
    {
        return method_exists($source, 'getParentRecord')
            ? $source->getParentRecord()
            : $source;
    }
}
