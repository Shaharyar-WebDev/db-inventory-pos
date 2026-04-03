<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Schemas;

use App\Models\Inventory\StockTransfer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockTransferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }
}
