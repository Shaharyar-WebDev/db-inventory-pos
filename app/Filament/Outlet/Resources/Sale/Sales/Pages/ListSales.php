<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use App\Filament\Outlet\Resources\Sale\Sales\Widgets\SaleStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         SaleStats::class,
    //     ];
    // }
}
