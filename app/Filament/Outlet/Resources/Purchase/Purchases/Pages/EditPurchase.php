<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Pages;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;
use App\Filament\Outlet\Resources\Purchase\Purchases\Action\CreatePurchaseReturnAction;
use App\Filament\Outlet\Resources\Purchase\Purchases\PurchaseResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            CreatePurchaseReturnAction::make(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
