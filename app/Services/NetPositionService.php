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
    public static function calculate(
        ?int $outletId = null,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
    ): array {
        $receivable = CustomerLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->sum('amount');

        $stock = InventoryLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->sum('value');

        $accounts = AccountLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->sum('amount');

        $liabilities = SupplierLedger::query()
            ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
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

    public static function calculateAllOutlets(
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
    ): array {
        $outlets = Outlet::all();
        $rows = [];

        foreach ($outlets as $outlet) {
            $data   = self::calculate($outlet->id, $startDate, $endDate);
            $rows[] = array_merge(['outlet' => $outlet->name], $data);
        }

        return $rows;
    }
}
