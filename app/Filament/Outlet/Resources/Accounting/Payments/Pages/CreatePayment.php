<?php

namespace App\Filament\Outlet\Resources\Accounting\Payments\Pages;

use App\Filament\Outlet\Resources\Accounting\Payments\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
