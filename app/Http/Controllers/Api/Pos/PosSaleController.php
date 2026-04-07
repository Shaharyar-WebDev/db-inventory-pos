<?php

namespace App\Http\Controllers\Api\Pos;

use App\Enums\ReceiptStatus;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Receipt;
use App\Models\Accounting\ReceiptSale;
use App\Models\Master\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosSaleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $itemsTotal = collect($request->input('items', []))
            ->sum(fn($i) => ($i['qty'] ?? 0) * ($i['rate'] ?? 0));

        $validated = $request->validate([
            'outlet_id'          => 'required|exists:outlets,id',
            'customer_id'        => 'required|exists:customers,id',
            'description'        => 'nullable|string|max:500',
            'discount_type'      => 'nullable|in:fixed,percent',
            'pos_receipt_number' => 'nullable|string|unique:sales,pos_receipt_number',
            'discount_value'     => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $itemsTotal) {
                    $type   = $request->discount_type ?? 'fixed';
                    $amount = $type === 'percent'
                        ? ($itemsTotal * $value / 100)
                        : $value;

                    if ($amount >= $itemsTotal) {
                        $fail('Discount amount cannot exceed or equal the subtotal.');
                    }
                }
            ],
            'delivery_charges'   => 'nullable|numeric|min:0',
            'tax_charges'        => 'nullable|numeric|min:0',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_id'    => 'nullable|exists:units,id',
            'items.*.qty'        => 'required|numeric|min:0.01',
            'items.*.rate'       => 'required|numeric|min:0.01',
            // 'payment_method_id' => 'required|exists:payment_methods,id',
            'account_id'        => 'required|exists:accounts,id',
            'amount_paid'       => 'required|numeric|min:0',
        ]);

        // Authorize — user must belong to this outlet
        $outlet = $request->user()
            ->outlets()
            ->findOrFail($validated['outlet_id']);

        // Recalculate server-side from validated data
        $itemsTotal     = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['rate']);
        $discountValue  = $validated['discount_value'] ?? 0;
        $discountType   = $validated['discount_type'] ?? 'fixed';
        $discountAmount = $discountType === 'percent'
            ? ($itemsTotal * $discountValue / 100)
            : $discountValue;

        $grandTotal = $itemsTotal
            - $discountAmount
            + ($validated['delivery_charges'] ?? 0)
            + ($validated['tax_charges'] ?? 0);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'outlet_id'        => $outlet->id,
                'customer_id'      => $validated['customer_id'],
                'description'      => $validated['description'] ?? null,
                'total'            => $itemsTotal,
                'discount_type'    => $discountType,
                'discount_value'   => $discountValue,
                'pos_receipt_number' => $validated['pos_receipt_number'] ?? null,
                'discount_amount'  => $discountAmount,
                'delivery_charges' => $validated['delivery_charges'] ?? 0,
                'tax_charges'      => $validated['tax_charges'] ?? 0,
                'grand_total'      => $grandTotal,
                'is_pos' => true,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $currentStock = $product->ledgers()
                    ->where('outlet_id', $outlet->id)
                    ->sum('qty');

                $requiredQty = $product->toBaseQty(
                    $item['qty'],
                    $item['unit_id'] ?? $product->unit_id
                );

                if ($requiredQty > $currentStock) {
                    DB::rollBack();
                    return response()->json([
                        'message'    => "Insufficient stock for [{$product->name}]. Available: {$currentStock}, Required: {$requiredQty}",
                        'product_id' => $product->id,
                    ], 422);
                }

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'unit_id'    => $item['unit_id'] ?? null,
                    'qty'        => $item['qty'],
                    'rate'       => $item['rate'],
                    'total'      => $item['qty'] * $item['rate'],
                ]);
            }

            $amountPaid = $validated['amount_paid'];

            if ($amountPaid > 0) {
                $receipt = Receipt::create([
                    'customer_id' => $validated['customer_id'],
                    'account_id'  => $validated['account_id'],
                    'amount'      => $amountPaid,
                    'outlet_id'   => $outlet->id,
                    'status'      => ReceiptStatus::APPROVED, // auto-approve POS payments
                    'remarks'     => "POS Payment received From customer {$sale->customer->name}",
                ]);

                ReceiptSale::create([
                    'receipt_id' => $receipt->id,
                    'sale_id'    => $sale->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'message'     => 'Sale saved',
                'sale_id'     => $sale->id,
                'sale_number' => $sale->sale_number,
                'grand_total' => $grandTotal,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Sale failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
