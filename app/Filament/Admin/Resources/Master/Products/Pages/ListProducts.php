<?php

namespace App\Filament\Admin\Resources\Master\Products\Pages;

use App\Filament\Admin\Resources\Master\Products\ProductResource;
use App\Filament\Admin\Resources\Master\Products\Widgets\ProductStats;
use App\Support\Actions\RefreshAction;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RefreshAction::make(),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }
}
