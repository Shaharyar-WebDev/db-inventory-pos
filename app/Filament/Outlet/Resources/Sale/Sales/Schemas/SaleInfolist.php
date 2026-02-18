<?php
namespace App\Filament\Outlet\Resources\Sale\Sales\Schemas;

use App\Filament\Outlet\Resources\Sale\SaleReturns\SaleReturnResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Section::make()
                            ->columns(2)
                            ->icon(Heroicon::ShoppingBag)
                            ->description('Sale Info')
                            ->collapsible()
                            ->schema([
                                TextEntry::make('customer.name')->label('Customer'),
                                TextEntry::make('total')->currency(),
                                TextEntry::make('tax_charges')->currency(),
                                TextEntry::make('delivery_charges')->currency(),
                                TextEntry::make('discount_type'),
                                TextEntry::make('discount_value'),
                                TextEntry::make('discount_amount')->currency(),
                                TextEntry::make('grand_total')->currency(),
                            ]),
                        Section::make()
                            ->columns(2)
                            ->icon(Heroicon::CurrencyDollar)
                            ->description('Financials')
                            ->collapsible()
                            ->live()
                            ->schema([
                                TextEntry::make('cogs')->currency(),
                                TextEntry::make('revenue')->currency(),
                                TextEntry::make('gross_profit')->currency(),
                                TextEntry::make('net_profit')->currency(),
                                TextEntry::make('gross_margin')->currency()->prefix('')->suffix(' %'),
                                TextEntry::make('net_margin')->currency()->prefix('')->suffix(' %'),
                            ]),
                        RepeatableEntry::make('items')
                            ->label('Sale Items')
                            ->columnSpanFull()
                            ->table([
                                TableColumn::make('Product'),
                                TableColumn::make('Unit'),
                                TableColumn::make('Quantity'),
                                TableColumn::make('Selling Price'),
                                TableColumn::make('Rate'),
                                TableColumn::make('Discount'),
                                TableColumn::make('Total'),
                            ])
                            ->schema([
                                TextEntry::make('product.name'),
                                TextEntry::make('unit.name'),
                                TextEntry::make('qty'),
                                TextEntry::make('product.selling_price')->currency(),
                                TextEntry::make('rate')->currency(),
                                TextEntry::make('discount')->currency()->state(fn($record) => ($record->product->selling_price * $record->qty) - $record->total),
                                TextEntry::make('total')->currency(),
                            ]),
                        RepeatableEntry::make('saleReturns')
                            ->label('Sale Returns')
                            ->columnSpanFull()
                            ->table([
                                TableColumn::make('Reference'),
                                TableColumn::make('Total Cogs'),
                                TableColumn::make('Grand Total'),
                                TableColumn::make('Discount Amount'),
                                TableColumn::make('Description'),
                            ])
                            ->schema([
                                TextEntry::make('return_number')
                                    ->url(fn(Model $record) => SaleReturnResource::getUrl('edit', [
                                        'record' => $record,
                                    ]), true),
                                TextEntry::make('total')->label('Toal Cogs')->currency(),
                                TextEntry::make('grand_total')->currency(),
                                TextEntry::make('discount_amount')->currency(),
                                TextEntry::make('description')->default('-'),
                            ]),
                    ]),
            ]);
    }
}
