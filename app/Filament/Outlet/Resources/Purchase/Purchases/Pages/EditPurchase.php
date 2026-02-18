<?php
namespace App\Filament\Outlet\Resources\Purchase\Purchases\Pages;

use App\Filament\Outlet\Resources\Purchase\Purchases\Action\CreatePurchaseReturnAction;
use App\Filament\Outlet\Resources\Purchase\Purchases\PurchaseResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
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
            PdfDownloadAction::make('partials.pdf.purchase-order', fn(Model $record) => $record->purchase_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.purchase-order', fn(Model $record) => $record->purchase_number)
                ->print(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
