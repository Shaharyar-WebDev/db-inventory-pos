<?php
namespace App\Filament\Outlet\Resources\Accounting\Receipts\Pages;

use App\Filament\Outlet\Resources\Accounting\Receipts\ReceiptResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.receipt', fn(Model $record) => $record->receipt_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.receipt', fn(Model $record) => $record->receipt_number)
                ->print(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
