<?php

namespace App\Filament\Outlet\Resources\Master\Customers\Pages;

use App\Filament\Outlet\Resources\Master\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
