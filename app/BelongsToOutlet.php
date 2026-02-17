<?php

namespace App;

use App\Models\Traits\BelongsToOutlet as BTO;

trait BelongsToOutlet
{
    /**
     * @deprecated Use App\Models\Traits\BelongsToOutlet directly instead
     * This proxy exists for backward compatibility only
     */
    use BTO;
}
