<?php

namespace App\Filament\Admin\Resources\Master\Products\Pages;

use Livewire\Livewire;
use Filament\Actions\CreateAction;
use App\Support\Actions\RefreshAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Master\Products\ProductResource;
use App\Filament\Admin\Resources\Master\Products\Widgets\ProductStats;

class ListProducts extends ListRecords
{
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
