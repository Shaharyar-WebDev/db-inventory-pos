<?php

namespace App\Models\Outlet;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Outlet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'is_active',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

}
