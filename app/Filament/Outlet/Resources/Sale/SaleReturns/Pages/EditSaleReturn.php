<?php
namespace App\Filament\Outlet\Resources\Sale\SaleReturns\Pages;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSaleReturn extends EditRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.sale-return', fn(Model $record) => $record->return_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.sale-return', fn(Model $record) => $record->return_number)
                ->print(),
        ];
    }
}
