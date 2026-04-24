<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Schemas;

use App\Models\Purchase\Purchase;
use Filament\Schemas\Schema;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema;
    }
}
