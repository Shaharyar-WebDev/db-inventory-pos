<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\Action;
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
            Action::make('create_sale_return')
                ->icon('heroicon-o-arrow-uturn-left')
                ->url(function (Model $record) {
                    return SaleReturnResource::getUrl('create',  ['sale_id' => $record->id]);
                }, true),
            Action::make('view_sale_returns')
                ->icon('heroicon-o-arrow-uturn-left')
                ->url(function (Model $record) {
                    return SaleReturnResource::getUrl('index',  ['filters' => [
                        'sale' => [
                            'value' => $record?->id
                        ]
                    ]]);
                }, true),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.sale', fn(Model $record) => $record->sale_number)
                ->print(),
        ];
    }
}
