<?php

namespace App\Filament\Outlet\Resources\Master\Products\Pages;

use App\Filament\Admin\Resources\Master\Products\Widgets\ProductStats;
use App\Filament\Outlet\Pages\ProductSalesOverview;
use App\Filament\Outlet\Resources\Master\Products\ProductResource;
use App\Support\Actions\RefreshAction;
use Filament\Actions\Action;
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
            // CreateAction::make()->modalWidth('7xl'),
            // Action::make('products_report')
            //     ->url(ProductSalesOverview::getUrl(), false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }
}
