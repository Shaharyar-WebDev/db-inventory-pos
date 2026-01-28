<?php

namespace App\Filament\Admin\Resources\Master\Products\Actions;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class ViewProductStockByOutletAction
{
    public static function make()
    {
        return Action::make('view_outlet_stock')
            ->modalHeading('Stock by Outlet')
            ->modalSubmitAction(false)
            ->modalWidth('lg')
            ->schema(function ($record) {
                $stocks = $record->stockByOutlet()->get();
                return [
                    Section::make()
                        ->columns(3)
                        ->schema(
                            $stocks && $stocks->isNotEmpty() ? $stocks->map(function ($row) use ($record) {
                                return TextEntry::make("outlet_{$row->outlet_id}")
                                    ->label($row->outlet->name)
                                    ->state(number_format($row->stock, 2).' '.($record->unit?->symbol ?? ''))
                                    ->weight('bold')
                                    ->color(
                                        $row->stock <= 0
                                            ? 'danger'
                                            : ($row->stock < 10 ? 'warning' : 'success')
                                    );
                            })->toArray() :
                            [TextEntry::make('no_stock')]
                        ),
                ];
            });
    }
}
