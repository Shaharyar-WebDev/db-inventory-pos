<?php

namespace App\Models\Outlet;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutletUser extends Model
{
    protected $table = 'outlet_user';

    protected $fillable = [
        'user_id',
        'outlet_id',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
