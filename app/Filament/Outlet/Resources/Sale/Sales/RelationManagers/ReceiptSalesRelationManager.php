<?php
namespace App\Filament\Outlet\Resources\Sale\Sales\RelationManagers;

use App\Filament\Outlet\Resources\Accounting\Receipts\ReceiptResource;
use App\Filament\Outlet\Resources\Accounting\Receipts\Schemas\ReceiptForm;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class ReceiptSalesRelationManager extends RelationManager
{
    protected static string $relationship = 'receiptSales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('receipt_id')
                    ->columnSpanFull()
                    ->required()
                    ->visible(fn($livewire) => ! self::shouldShowOnResource($livewire, ReceiptResource::class))
                    ->manageOptionForm(fn() => ReceiptForm::getFormForSaleReceipt($this->getOwnerRecord()))
                    ->rule(function ($record) {
                        return Rule::unique('receipt_sales', 'sale_id')
                            ->where('receipt_id', $this->ownerRecord->id)
                            ->ignore($record?->id);
                    })
                    ->relationship('receipt', 'receipt_number'),
                Select::make('sale_id')
                    ->visible(fn($livewire) => self::shouldShowOnResource($livewire, ReceiptResource::class))
                    ->columnSpanFull()
                    ->required()
                    ->rule(function ($record) {
                        return Rule::unique('receipt_sales', 'receipt_id')
                            ->where('sale_id', $this->ownerRecord->id)
                            ->ignore($record?->id);
                    })
                    ->relationship('sale', 'sale_number'),
            ]);
    }

    private function shouldShowOnResource($livewire, $resource): bool
    {
        return $livewire->pageClass::getResource() === $resource;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('receipt.receipt_number')
            ->columns([
                TextColumn::make('receipt.receipt_number')
                    ->url(fn($record) => ReceiptResource::getUrl('edit', [
                        'record' => $record->receipt,
                    ]), true)
                    ->visible(fn($livewire) => ! self::shouldShowOnResource($livewire, ReceiptResource::class))
                    ->searchable(),
                TextColumn::make('sale.sale_number')
                    ->url(fn($record) => SaleResource::getUrl('edit', [
                        'record' => $record->sale,
                    ]), true)
                    ->visible(fn($livewire) => self::shouldShowOnResource($livewire, ReceiptResource::class))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
