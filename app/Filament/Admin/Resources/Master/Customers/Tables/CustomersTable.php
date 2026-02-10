<?php

namespace App\Filament\Admin\Resources\Master\Customers\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerLedgerExport;
use App\Exports\SupplierLedgerExport;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->imageSize(80)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('contact')
                    ->disableNumericFormatting()
                    ->copyable(),
                TextColumn::make('city.name')
                    ->copyable(),
                TextColumn::make('area.name')
                    ->copyable(),
                TextColumn::make('opening_balance')
                    ->currency()
                    ->copyable(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->searchable(false)
                    ->copyable(),
                TextColumn::make('address')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
                        $fileName = "customer_ledger_{$record->name}{$suffix}.xlsx";
                        return Excel::download(new CustomerLedgerExport(
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
