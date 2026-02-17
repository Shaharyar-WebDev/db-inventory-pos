<?php

namespace App\Models\Outlet;

use App\Models\Outlet\Outlet as OutletModel;
use App\Models\Traits\HasStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use HasStatus
        // ,SoftDeletes
    ;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'status',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    // public static $disableStatusScope = true;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function options()
    {
        return OutletModel::get()->pluck('name', 'id');
    }
}
