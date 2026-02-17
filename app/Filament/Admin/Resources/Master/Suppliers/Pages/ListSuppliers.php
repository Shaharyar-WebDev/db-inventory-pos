<?php

namespace App\Filament\Admin\Resources\Master\Suppliers\Pages;

use App\Filament\Admin\Resources\Master\Suppliers\SupplierResource;
use App\Support\Actions\RefreshAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            RefreshAction::make(),
        ];
    }
}
