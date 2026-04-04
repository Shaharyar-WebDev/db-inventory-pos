<?php

namespace App\Http\Controllers\Api\Pos;

use App\Http\Controllers\Controller;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosSaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id'  => 'required|exists:outlets,id',
            'items'      => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|numeric|min:0.01',
            'items.*.rate'       => 'required|numeric|min:0',
            'items.*.total'      => 'required|numeric|min:0',
            'grand_total'        => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'outlet_id'    => $request->outlet_id,
                'customer_id'  => $request->customer_id,
                'total'        => $request->grand_total,
                'grand_total'  => $request->grand_total,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'unit_id'    => $item['unit_id'] ?? null,
                    'qty'        => $item['qty'],
                    'rate'       => $item['rate'],
                    'total'      => $item['total'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Sale saved', 'sale_id' => $sale->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Sale failed', 'error' => $e->getMessage()], 500);
        }
    }
}
