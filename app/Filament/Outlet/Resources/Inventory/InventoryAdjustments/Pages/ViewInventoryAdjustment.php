<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages;

use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\InventoryAdjustmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInventoryAdjustment extends ViewRecord
{
    protected static string $resource = InventoryAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
