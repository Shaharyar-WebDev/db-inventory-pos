<?php
namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->print(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
