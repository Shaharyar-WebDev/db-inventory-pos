<?php

namespace App\Models\Traits;

trait HasSelfAsParent
{
    public function getParentRecord(): static
    {
        return $this;
    }
}
