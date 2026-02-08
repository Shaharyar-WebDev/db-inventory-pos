<?php

namespace App\Filament\Outlet\Resources\Purchase\PurchaseReturns\Pages;

use Illuminate\Http\Request;
use App\Models\Purchase\Purchase;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Outlet\Resources\Purchase\PurchaseReturns\PurchaseReturnResource;

class CreatePurchaseReturn extends CreateRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    public function mount(): void
    {
        parent::mount();

        $purchaseeId = request()->query('purchase_id');
        $purchase = Purchase::with('items')->find($purchaseeId);

        if (!$purchase) {
            abort(404, 'Purchase not found');
        }

        $this->form->fill([
            'purchase_id' => $purchase->id,
            'grand_total' => $purchase->items->sum('total'),
        ]);
    }

    protected function afterCreate(): void
    {
        $return = $this->record;
        $total = $return->items
            ->sum(fn($item) => ($item['qty'] ?? 0) * ($item['rate'] ?? 0));
        $return->grand_total = $total;
        $return->save();
    }
}
