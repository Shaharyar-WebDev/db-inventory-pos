<?php

namespace App\Filament\Admin\Resources\Master\Customers\Pages;

use App\Filament\Admin\Resources\Master\Customers\Actions\CustomerLedgerExport;
use App\Filament\Admin\Resources\Master\Customers\Actions\CustomerLedgerExportAction;
use App\Filament\Admin\Resources\Master\Customers\CustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
            DeleteAction::make(),
            CustomerLedgerExportAction::make(),
        ];
    }
}
