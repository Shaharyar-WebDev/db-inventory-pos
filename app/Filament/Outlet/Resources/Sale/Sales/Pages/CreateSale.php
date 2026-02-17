<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
        // dd($data);

        // return $data;
    // }
}
