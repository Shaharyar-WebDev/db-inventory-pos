<?php

namespace App\Models;

use App\Models\Outlet\Outlet;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
