<?php

namespace App\Filament\Outlet\Resources\Master\Products\Pages;

use App\Filament\Outlet\Resources\Master\Products\ProductResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
