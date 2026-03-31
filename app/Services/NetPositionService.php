<?php

namespace App\Services;

use App\Models\Accounting\AccountLedger;
use App\Models\Accounting\CustomerLedger;
use App\Models\Accounting\SupplierLedger;
use App\Models\Inventory\InventoryLedger;
use App\Models\Outlet\Outlet;
use Carbon\Carbon;

class NetPositionService
{
    public static function calculate(?int $outletId = null): array
    {
        $receivable = CustomerLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->sum('amount');

        $stock = InventoryLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->sum('value');

        $accounts = AccountLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->sum('amount');

        $liabilities = SupplierLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->sum('amount');

        $totalAssets = $receivable + $stock + $accounts;
        $netPosition = $totalAssets - $liabilities;

        return [
            'receivable'   => $receivable,
            'stock'        => $stock,
            'accounts'     => $accounts,
            'liabilities'  => $liabilities,
            'total_assets' => $totalAssets,
            'net_position' => $netPosition,
        ];
    }

    public static function calculateAllOutlets(): array {
        $outlets = Outlet::all();
        $rows = [];

        foreach ($outlets as $outlet) {
            $data   = self::calculate($outlet->id);
            $rows[] = array_merge(['outlet' => $outlet->name], $data);
        }

        return $rows;
    }
}
