<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Pages;

use App\Filament\Admin\Resources\Inventory\StockTransfers\StockTransferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockTransfer extends CreateRecord
{
    protected static string $resource = StockTransferResource::class;
}
