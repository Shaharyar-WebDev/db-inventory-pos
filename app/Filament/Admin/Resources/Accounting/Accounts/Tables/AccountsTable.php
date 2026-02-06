<?php

namespace App\Filament\Admin\Resources\Accounting\Accounts\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use App\Exports\AccountLedgerExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use App\Exports\InventoryLedgerExport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('opening_balance')
                    ->currency(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->searchable(false)
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
                Action::make('export_ledger')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    // ->schema([
                    //     Select::make('outlet_id')
                    //         ->label('Outlet')
                    //         ->options(Outlet::options())
                    //     // ->required(),
                    // ])
                    ->action(function (Model $record, array $data) {
                        $outletId = $data['outlet_id'] ?? null;
                        $outlet = Outlet::find($outletId);
                        $suffix = $outlet ? "-{$outlet->name}" : '';
                        $fileName = "account_ledger_{$record->name}{$suffix}.xlsx";
                        return Excel::download(new AccountLedgerExport(
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
