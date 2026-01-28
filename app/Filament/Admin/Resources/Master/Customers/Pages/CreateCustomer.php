<?php

namespace App\Filament\Admin\Resources\Master\Customers\Pages;

use App\Filament\Admin\Resources\Master\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
