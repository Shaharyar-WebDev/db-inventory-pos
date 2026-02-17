<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Filament\Admin\Resources\Master\Products\Widgets\ProductStats;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
