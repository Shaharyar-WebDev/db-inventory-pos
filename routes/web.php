<?php

use Illuminate\Support\Facades\Route;
use App\Models\Inventory\InventoryLedger;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('hello', function () {
    $ledgers = InventoryLedger::query()
        ->orderBy('created_at')
        ->orderBy('id')
        ->where('outlet_id', 1)
        // ->where('product_id', 1)
        ->get();

    $balance = 0;

    $rows = $ledgers->map(function ($row) use (&$balance) {

        $in  = $row->qty > 0 ? $row->qty : '';
        $out = $row->qty < 0 ? abs($row->qty) : '';

        $balance += $row->qty;

        return [
            'date'      => $row->created_at->format('Y-m-d H:i'),
            'reference' => class_basename($row->source_type) . '-' . $row->source_id,
            'product' => $row->product->name,
            'in'        => $in,
            'out'       => $out,
            'rate'      => $row->rate,
            'value'     => $row->value,
            'balance'   => $balance,
            'remarks'   => $row->remarks,
            'outlet' => $row->outlet->name
        ];
    });

    $html = '
    <style>
        table { border-collapse: collapse; width: 100%; font-family: monospace; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: right; }
        th { background: #f4f4f4; }
        td.left { text-align: left; }
    </style>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                 <th>Product</th>
                <th>In</th>
                <th>Out</th>
                <th>Rate</th>
                <th>Value</th>
                <th>Balance</th>
                <th>Remarks</th>
                <th>Outlet</th>
            </tr>
        </thead>
        <tbody>
    ';

    foreach ($rows as $row) {
        $html .= "
            <tr>
                <td class='left'>{$row['date']}</td>
                <td class='left'>{$row['reference']}</td>
                   <td>{$row['product']}</td>
                <td>{$row['in']}</td>
                <td>{$row['out']}</td>
                <td>{$row['rate']}</td>
                <td>{$row['value']}</td>
                <td>{$row['balance']}</td>
                <td class='left'>{$row['remarks']}</td>
                <th>{$row['outlet']}</th>
            </tr>
        ";
    }

    $html .= '
        </tbody>
    </table>';

    return $html;
});
