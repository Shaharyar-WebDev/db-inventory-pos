<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages;

use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\InventoryAdjustmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryAdjustments extends ListRecords
{
    protected static string $resource = InventoryAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
