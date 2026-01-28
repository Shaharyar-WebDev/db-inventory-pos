<?php

namespace App\Filament\Admin\Resources\Master\Customers\Pages;

use App\Filament\Admin\Resources\Master\Customers\CustomerResource;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
