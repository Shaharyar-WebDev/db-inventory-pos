<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Pages;

use App\Filament\Admin\Resources\Inventory\StockTransfers\StockTransferResource;
use App\Support\Actions\PdfDownloadAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStockTransfer extends EditRecord
{
    protected static string $resource = StockTransferResource::class;

    public function print()
    {
        $record = $this->record;

        $html = view('partials.pdf.stock-transfer', [
            'record' => $record
        ])->render();

        dd();

        return response()->streamDownload(
            fn() => print(
                Pdf::loadHTML($html)
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setPaper('A4', 'portrait')
                ->output()
            ),
            $record->transfer_number . '.pdf'
        );
    }


    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.stock-transfer', fn(Model $record) => $record->transfer_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.stock-transfer', fn(Model $record) => $record->transfer_number)
                ->print()
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
