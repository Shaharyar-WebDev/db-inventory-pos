<?php

namespace App\Services;

use App\Models\Accounting\AccountLedger;
use App\Models\Accounting\CustomerLedger;
use App\Models\Accounting\ExpenseLedger;
use App\Models\Accounting\SupplierLedger;
use App\Models\Inventory\InventoryLedger;
use App\Models\Outlet\Outlet;
use App\Models\Scopes\OutletScope;
use Carbon\Carbon;

class NetPositionService
{
    public static function calculate(): array
    {
        // --- ASSETS ---

        // Global — ignore outlet scope
        $receivable = CustomerLedger::withoutGlobalScope(OutletScope::class)
            ->sum('amount');

        // Per outlet — stock broken down
        $outlets = Outlet::all();
        $stockByOutlet = [];
        $totalStock = 0;

        foreach ($outlets as $outlet) {
            $value = InventoryLedger::where('outlet_id', $outlet->id)
                ->sum('value');
            $stockByOutlet[] = [
                'outlet' => $outlet->name,
                'value'  => $value,
            ];
            $totalStock += $value;
        }

        // Global — accounts
        $accounts = AccountLedger::withoutGlobalScope(OutletScope::class)
            ->sum('amount');

        $totalAssets = $receivable + $totalStock + $accounts;

        // --- LIABILITIES ---

        // Global — suppliers
        $liabilities = SupplierLedger::withoutGlobalScope(OutletScope::class)
            ->sum('amount');

        $totalLiabilities = $liabilities;
        $netPosition      = $totalAssets - $totalLiabilities;

        return [
            // assets
            'receivable'        => $receivable,
            'stock_by_outlet'   => $stockByOutlet,
            'total_stock'       => $totalStock,
            'accounts'          => $accounts,
            'total_assets'      => $totalAssets,

            // liabilities
            'liabilities'       => $liabilities,
            'total_liabilities' => $totalLiabilities,

            // final
            'net_position'      => $netPosition,
        ];
    }
}
