<?php
namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\EditAction;
use Filament\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            // ...
            ViewSale::class,
            EditSale::class,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->print(),
        ];
    }
}
