<?php

namespace App\Filament\Admin\Resources\Outlet\Outlets\Pages;

use App\Filament\Admin\Resources\Outlet\Outlets\OutletResource;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOutlet extends ViewRecord
{
    protected static string $resource = OutletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
