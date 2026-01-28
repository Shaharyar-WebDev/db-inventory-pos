<?php

namespace App;

use App\Enums\PanelId;
use App\Models\Outlet\Outlet;
use Filament\Facades\Filament;
use App\Models\Scopes\OutletScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToOutlet
{
    public static function bootBelongsToOutlet()
    {
        $currentPanel = Filament::getCurrentPanel();

        if ($currentPanel && $currentPanel->getId() === PanelId::OUTLET->id()) {

            static::addGlobalScope(new OutletScope);

            static::creating(function ($model) {
                if (!in_array('outlet_id', $model->getFillable())) {
                    $model->fillable(array_merge($model->getFillable(), ['outlet_id']));
                }

                if (! $model->outlet_id) {
                    $model->outlet_id = Filament::getTenant()->id;
                }
            });

        }
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class)->where('is_active', true);
    }
}
