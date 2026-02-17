<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasAuditUsers
{
    public static function bootHasAuditUsers()
    {
        static::creating(function ($model) {
            $userId = Auth::id();

            if ($userId) {
                $model->creator_id ??= $userId;
                $model->updater_id ??= $userId;
            }
        });

        static::updating(function ($model) {
            $userId = Auth::id();

            if ($userId) {
                $model->updater_id = $userId;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
}
