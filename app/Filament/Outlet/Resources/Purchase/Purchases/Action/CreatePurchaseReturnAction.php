<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Action;

use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class CreatePurchaseReturnAction
{
    public static function make()
    {
        return Action::make('purchase_return')
            ->icon('heroicon-o-arrow-uturn-left')
            ->url(function (Model $record) {
                return PurchaseReturnResource::getUrl('create',  ['purchase_id' => $record->id]);
            }, true)
            ->authorize(function () {
                return filament()->auth()->user()->can('Create:PurchaseReturn');
            });
    }
}
