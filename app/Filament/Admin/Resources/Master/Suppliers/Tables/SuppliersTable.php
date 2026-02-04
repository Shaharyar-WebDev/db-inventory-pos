<?php

namespace App\Filament\Admin\Resources\Master\Suppliers\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierLedgerExport;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use App\Exports\InventoryLedgerExport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('contact')
                    ->disableNumericFormatting()
                    ->copyable(),
                TextColumn::make('opening_balance')
                    ->currency()
                    ->copyable(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->searchable(false)
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('export_ledger')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->schema([
                        Select::make('outlet_id')
                            ->label('Outlet')
                            ->options(Outlet::options())
                        // ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        $outletId = $data['outlet_id'];
                        $outlet = Outlet::find($outletId);
                        $suffix = $outlet ? "-{$outlet->name}" : '';
                        $fileName = "supplier_ledger_{$record->name}{$suffix}.xlsx";
                        return Excel::download(new SupplierLedgerExport(
                            $record->id,
                            $outletId,
                        ), $fileName);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
