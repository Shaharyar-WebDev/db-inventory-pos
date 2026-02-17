<?php

namespace App\Models\Traits;

use App\Enums\PanelId;
use App\Enums\Status;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    public function initializeHasStatus(): void
    {
        $this->casts['status'] = Status::class;
        $this->fillable[] = 'status';
    }

    protected static function bootHasStatus(): void
    {
        // $isStatusScopeDisabled = static::$disableStatusScope ?? false;

        // if (!$isStatusScopeDisabled) {
        //     $currentPanel = Filament::getCurrentPanel();

        //     if ($currentPanel && $currentPanel->getId() === PanelId::OUTLET->id()) {
        //         static::addGlobalScope('active_status', function (Builder $builder) {
        //             $builder->where('status', Status::ACTIVE->value);
        //         });
        //     }
        // }
    }
}
