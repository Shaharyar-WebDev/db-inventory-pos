<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Enums\PanelId;
use App\Enums\UserRole;
use App\Models\Outlet\Outlet;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRole::SUPER_ADMIN->value);
    }

    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class)->where('is_active', true);
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->isSuperAdmin()) {
            return Outlet::query()->get();
        }

        return $this->outlets;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        // dd($this);
        if ($this->isSuperAdmin()) {
            return true; // super admins can access any outlet
        }

        return $this->outlets()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if($panel->getId() === PanelId::ADMIN->id()){
            return $this->isSuperAdmin();
        };

        return true;
    }
}
