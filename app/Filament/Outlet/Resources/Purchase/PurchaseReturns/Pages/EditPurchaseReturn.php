<?php
namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPurchaseReturn extends EditRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.purchase-return', fn(Model $record) => $record->return_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.purchase-return', fn(Model $record) => $record->return_number)
                ->print(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
