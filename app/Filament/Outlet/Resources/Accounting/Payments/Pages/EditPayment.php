<?php
namespace App\Filament\Outlet\Resources\Accounting\Payments\Pages;

use App\Filament\Outlet\Resources\Accounting\Payments\PaymentResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.payment', fn(Model $record) => $record->payment_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.payment', fn(Model $record) => $record->payment_number)
                ->print(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
