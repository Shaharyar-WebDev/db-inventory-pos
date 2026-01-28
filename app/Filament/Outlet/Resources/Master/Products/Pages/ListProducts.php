<?php

namespace App\Filament\Outlet\Resources\Master\Products\Pages;

use App\Filament\Outlet\Resources\Master\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make()->modalWidth('7xl'),
        ];
    }
}
