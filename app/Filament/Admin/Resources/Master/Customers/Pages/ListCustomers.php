<?php

namespace App\Filament\Admin\Resources\Master\Customers\Pages;

use App\Exports\CustomerExampleExport;
use App\Exports\CustomerExport;
use App\Filament\Admin\Resources\Master\Customers\CustomerResource;
use App\Imports\CustomerImport;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('import_customers')
                ->icon(Heroicon::ArrowDownTray)
                ->label('Import Customers')
                ->color('warning')
                ->schema([
                    FileUpload::make('file')
                        ->label('Excel File')
                        ->required()
                        ->hintAction(
                            Action::make('download_example')->label('Download Example')->icon(Heroicon::ArrowDownTray)
                                ->action(function () {
                                    return Excel::download(new CustomerExampleExport, 'customers-import.xlsx');
                                })
                        )
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                        ]),
                ])
                ->action(function (array $data) {
                    Excel::import(new CustomerImport, $data['file']);

                    Notification::make()
                        ->title('Customers imported successfully')
                        ->success()
                        ->send();
                }),
            LedgerExportAction::configure(CustomerExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    return "customer_export";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
            // LedgerExportAction::configure(CustomerBalancesExport::class)
            //     ->fileName(function (?Model $record, ?Outlet $outlet) {
            //         return "customer_balances_export";
            //     })
            //     ->isOutletRequired(false)
            //     ->hasOutletSelectionSchema(false)
            //     ->make(),
        ];
    }
}
