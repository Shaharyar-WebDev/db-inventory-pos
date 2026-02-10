<?php

namespace App\Filament\Admin\Resources\Master\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use App\Exports\InventoryLedgerExport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Admin\Resources\Master\Products\Actions\ViewProductStockByOutletAction;
use App\Filament\Admin\Resources\Master\Products\Actions\ViewProductValueByOutletAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->imageSize(80)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),
                TextColumn::make('category.name')
                    ->copyable(),
                TextColumn::make('brand.name')
                    ->copyable(),
                TextColumn::make('unit.name')
                    ->copyable(),
                TextColumn::make('cost_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('selling_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('current_stock')
                    ->default(0)
                    ->searchable(false)
                    ->suffix(fn($record) => ' ' . ($record->unit?->symbol ?? ''))
                    ->action(
                        ViewProductStockByOutletAction::make()
                    )
                    ->sortable(false),
                TextColumn::make('current_value')
                    ->currency()
                    ->searchable(false)
                    ->action(
                        ViewProductValueByOutletAction::make()
                    )
                    ->sortable(false),
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
                EditAction::make(),
                ViewProductStockByOutletAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('warning'),
                ViewProductValueByOutletAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
                        $fileName = "inventory_ledger_{$record->name}{$suffix}.xlsx";
                        return Excel::download(new InventoryLedgerExport(
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
