<?php

namespace App\Http\Controllers\Api\Pos;

use App\Http\Controllers\Controller;
use App\Models\Master\Product;
use App\Models\Master\Customer;
use App\Models\Accounting\PaymentMethod;
use Illuminate\Http\Request;

class PosBootstrapController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->header('X-Outlet-Id');

        $products = Product::with(['unit', 'category', 'brand'])
            ->withSum([
                'ledgers as current_stock' => fn($q) => $q->where('outlet_id', $outlet_id)
            ], 'qty')
            ->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'name'          => $p->name,
                'code'          => $p->code,
                'category_id'   => $p->category_id,
                'brand_id'      => $p->brand_id,
                'unit_id'       => $p->unit_id,
                'unit'          => $p->unit?->name,
                'category'      => $p->category?->name,
                'selling_price' => $p->selling_price,
                'current_stock' => $p->current_stock ?? 0,
            ]);

        $customers = Customer::select('id', 'name', 'contact', 'customer_type')->get();

        $payment_methods = PaymentMethod::select('id', 'name')->get();

        return response()->json([
            'products'        => $products,
            'customers'       => $customers,
            'payment_methods' => $payment_methods,
        ]);
    }
}
