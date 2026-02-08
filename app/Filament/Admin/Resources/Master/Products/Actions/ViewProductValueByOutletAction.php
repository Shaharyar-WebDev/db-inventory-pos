<?php

namespace App\Filament\Admin\Resources\Master\Products\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewProductValueByOutletAction
{
    public static function make()
    {
        return Action::make('view_outlet_value')
            ->modalHeading(fn(Model $record) => $record->name . ' Stock Value by Outlet')
            ->modalSubmitAction(false)
            ->modalWidth('lg')
            ->schema(function ($record) {
                // Get ledgers grouped by outlet
                $stocks = $record->valueByOutlet()->get();

                return [
                    Section::make()
                        ->columns(3)
                        ->schema(
                            $stocks && $stocks->isNotEmpty() ? $stocks->map(function ($row) use ($record) {
                                return TextEntry::make("outlet_{$row->outlet_id}")
                                    ->label($row->outlet->name)
                                    ->state(currency_format($row->value))
                                    ->weight('bold')
                                    ->color(
                                        $row->value <= 0
                                            ? 'danger'
                                            : ($row->value < 1000 ? 'warning' : 'success') // adjust thresholds as needed
                                    );
                            })->toArray() :
                                [TextEntry::make('no_stock_value')->state('No stock value')]
                        ),
                ];
            })
            ->extraModalFooterActions([
                Action::make('refresh')
                    ->action(function ($livewire) {
                        $livewire->refresh();
                    }),
            ]);
    }
}
