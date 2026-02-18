@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inventory Adjustment #{!! $record->adjustment_number !!}</title>
    <style>
        html,
        body {
            height: auto;
            overflow: visible;
            margin: 0;
            padding: 0;
        }

        /* strict CSS 2.1 with ULTRA spacing - luxury edition */
        body {
            margin: 0;
            padding: 0.7cm;
            font-family: 'Trebuchet MS', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #1e2b37;
            background: #ffffff;
            max-width: 100%;
        }

        .adjustment-wrapper {
            border: 1.5px solid #d9e0e6;
            background: #ffffff;
            padding: 0.7cm;
            position: relative;
            border-bottom: 2px solid #cbd2d9;
            border-right: 2px solid #cbd2d9;
        }

        /* CLEARFIX */
        .clearfix:before,
        .clearfix:after {
            content: "";
            display: table;
        }

        .clearfix:after {
            clear: both;
        }

        .clearfix {
            zoom: 1;
        }

        /* HEADER - luxury breathing */
        .header-left {
            float: left;
            width: 49%;
            margin: 0 0 0.5cm 0;
        }

        .header-right {
            float: right;
            width: 49%;
            margin: 0 0 0.5cm 0;
            text-align: right;
        }

        .company-name {
            font-size: 22pt;
            font-weight: normal;
            letter-spacing: 1px;
            color: #0b1c26;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            border-bottom: 2.5px solid #728f9b;
            display: inline-block;
            padding-bottom: 4px;
        }

        .company-detail {
            font-size: 9.5pt;
            color: #405b69;
            margin: 6px 0 0 0;
            line-height: 1.5;
        }

        /* document meta */
        .doc-label {
            font-size: 14pt;
            font-weight: bold;
            color: #2c4556;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0 0 2px 0;
        }

        .doc-number {
            font-size: 16pt;
            color: #1e2b37;
            margin: 0;
            font-weight: bold;
            border-bottom: 2.5px solid #2c4556;
            padding-bottom: 4px;
            display: inline-block;
        }

        .doc-meta {
            font-size: 9.5pt;
            color: #5b7482;
            margin-top: 0.2cm;
            line-height: 1.5;
        }

        /* OUTLET CARD */
        .outlet-card {
            width: 100%;
            margin: 0.3cm 0 0.5cm 0;
            border-collapse: collapse;
        }

        .outlet-card td {
            vertical-align: top;
            background: #f6f9fc;
            border: 1.5px solid #dde5ec;
            padding: 0.5cm;
        }

        .outlet-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1c3b4f;
            margin: 0 0 0.2cm 0;
            text-transform: uppercase;
            border-bottom: 2.5px solid #8ba0ae;
            padding-bottom: 4px;
            display: inline-block;
        }

        .outlet-detail {
            font-size: 11pt;
            color: #2a3f4d;
            line-height: 1.5;
            margin: 0.2cm 0 0 0;
        }

        .outlet-detail strong {
            color: #0f2938;
            font-size: 12pt;
        }

        .badge {
            background: #2c4556;
            color: white;
            padding: 4px 12px;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 10px;
        }

        /* description card - ultra breathing */
        .desc-card {
            background: #f0f5fa;
            border: 1.5px solid #c5d5e2;
            padding: 0.5cm;
            margin: 0.5cm 0;
        }

        .desc-label {
            font-size: 11pt;
            font-weight: bold;
            color: #1f3f52;
            text-transform: uppercase;
            margin: 0 0 6px 0;
        }

        .desc-text {
            font-size: 10pt;
            color: #2b4b5e;
            font-style: italic;
            line-height: 1.5;
        }

        /* ITEMS TABLE - premium spacing */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5cm 0;
            font-size: 10pt;
            table-layout: fixed;
        }

        .items-table th {
            background: #2c4556;
            color: white;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-size: 9.5pt;
            padding: 10px;
            border: 1.5px solid #1f3542;
            text-align: left;
        }

        .items-table td {
            padding: 10px;
            border: 1.5px solid #cddae3;
            color: #1f2e39;
            vertical-align: top;
        }

        .items-table tr.alt td {
            background: #f7fafd;
        }

        .qty-cell,
        .value-cell {
            text-align: right;
            font-weight: bold;
        }

        .positive {
            color: #2e7d32;
        }

        .negative {
            color: #c62828;
        }

        .product-sku {
            font-size: 8pt;
            color: #6f8a9c;
            display: block;
            line-height: 1.4;
            margin-top: 3px;
        }

        /* summary panel - ultra breathing */
        .summary-panel {
            float: right;
            width: 45%;
            margin: 0.3cm 0;
            border-collapse: collapse;
        }

        .summary-panel td {
            padding: 8px 10px;
            border: 1.5px solid #ccdae5;
            font-size: 10pt;
        }

        .summary-panel .label {
            background: #eef3f8;
            font-weight: bold;
            color: #1f3f52;
            text-align: left;
        }

        .summary-panel .value {
            text-align: right;
            background: #ffffff;
            font-weight: bold;
        }

        .summary-panel .total-row td {
            background: #d9e2ec;
            font-size: 13pt;
            font-weight: bold;
            color: #0d2635;
            border: 1.5px solid #9fb3c2;
            padding: 10px;
        }

        /* footer - ultra spaced */
        .footer-note {
            width: 100%;
            margin-top: 0.6cm;
            border-top: 2.5px solid #2c4556;
            padding-top: 0.5cm;
        }

        .signature-box {
            float: left;
            width: 40%;
        }

        .meta-box {
            float: right;
            width: 55%;
            text-align: right;
        }

        .signature-line {
            border-top: 2px solid #2c4556;
            width: 160px;
            margin-top: 18px;
            padding-top: 5px;
            font-size: 8.5pt;
            color: #4a6a7c;
        }

        .adjustment-stamp {
            background: #f0f5fa;
            padding: 7px 15px;
            border: 2px dashed #2c4556;
            display: inline-block;
            font-size: 17pt;
            font-weight: bold;
            color: #2c4556;
            letter-spacing: 3px;
            opacity: 0.8;
        }

        hr {
            border: 0;
            border-top: 2px solid #c0d2df;
            margin: 0.3cm 0;
        }

        /* override inline styles */
        div[style*="margin-top:0.7cm"] {
            margin-top: 0.25cm !important;
        }

        div[style*="height:0.1cm"] {
            height: 0.09cm !important;
        }

        /* keep single page with ultra breathing */
        * {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="adjustment-wrapper">

        <!-- HEADER -->
        <div class="clearfix">
            <div class="header-left">
                @php
                    $logo = $generalSettings->site_logo ?? '';
                    $logoUrl = filter_var($logo, FILTER_VALIDATE_URL)
                        ? $logo
                        : config('app.url') . '/storage/' . ltrim($logo, '/');
                    $logoPath = public_path(str_replace(config('app.url'), '', $logoUrl));
                    $logoExists = $logo && file_exists($logoPath);
                @endphp

                @if ($logoExists)
                    <img src="{{ $logoPath }}" alt="{{ $generalSettings->site_name ?? 'Logo' }}"
                        style="max-height: 2.5cm; max-width: 14cm; margin-bottom: 0.2cm; display: block;">
                @else
                    <div class="company-name">{{ $generalSettings->site_name ?? 'My App' }}</div>
                @endif
                <div class="company-detail">
                    {{-- {!! $generalSettings->address ?? 'Karachi' !!}<br>
                    {!! $generalSettings->contact ?? '03154573767' !!} --}}
                </div>
            </div>
            <div class="header-right">
                <div class="doc-label">inventory adjustment</div>
                <div class="doc-number">{!! $record->adjustment_number !!}</div>
                <div class="doc-meta">
                    Date: {!! $record->date ? $record->date->format(app_date_time_format()) : $record->created_at->format(app_date_time_format()) !!}<br>
                    @if ($record->created_at != $record->updated_at)
                        Last updated: {!! $record->updated_at->format(app_date_time_format()) !!}
                    @endif
                </div>
            </div>
        </div>

        <!-- Outlet Details -->
        <table class="outlet-card" cellspacing="0">
            <tr>
                <td>
                   <div class="outlet-title">outlet</div>
                    <div class="outlet-detail">
                        <strong>{!! $record->outlet->name !!}</strong><br>
                        {!! $record->outlet->address ?? 'Address not available' !!}<br>
                        @if ($record->outlet->phone_number)
                            tel: {!! $record->outlet->phone_number !!}
                        @endif
                    </div>
                    <div class="badge">adjustment location</div>
                </td>
            </tr>
        </table>

        <!-- DESCRIPTION / NOTES -->
        @if ($record->description)
            <div class="desc-card">
                <div class="desc-label">adjustment notes</div>
                <div class="desc-text">{!! nl2br(e($record->description)) !!}</div>
            </div>
        @endif

        <!-- ITEMS TABLE -->
        <table class="items-table" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="50%">product</th>
                    <th width="15%">quantity</th>
                    <th width="15%">rate</th>
                    <th width="15%">value</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalValue = 0;
                    $totalPositiveQty = 0;
                    $totalNegativeQty = 0;
                @endphp
                @foreach ($record->items as $index => $item)
                    @php
                        $avgRate = $item->product->getAvgRateAsOf($item->created_at) ?: $item->product->cost_price;
                        $value = $item->qty * $avgRate;
                        $totalValue += $value;

                        if ($item->qty > 0) {
                            $totalPositiveQty += $item->qty;
                        } else {
                            $totalNegativeQty += $item->qty;
                        }

                        $qtyClass = $item->qty > 0 ? 'positive' : ($item->qty < 0 ? 'negative' : '');
                    @endphp
                    <tr @if ($index % 2 == 1) class="alt" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if ($item->product->description)
                                <span class="product-sku">{{ Str::limit($item->product->description, 40) }}</span>
                            @endif
                        </td>
                        <td class="qty-cell {{ $qtyClass }}">
                            {{ qty_format($item->qty) . ' ' . $item->product->unit->symbol }}
                        </td>
                        <td class="qty-cell">{{ currency_format($avgRate) }}</td>
                        <td class="value-cell {{ $qtyClass }}">{{ currency_format($value) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY TOTALS -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Total items</td>
                    <td class="value">{{ $record->items->count() }}</td>
                </tr>
                @if($totalPositiveQty > 0)
                <tr>
                    <td class="label">Total additions</td>
                    <td class="value positive">{{ qty_format($totalPositiveQty) }} units</td>
                </tr>
                @endif
                @if($totalNegativeQty < 0)
                <tr>
                    <td class="label">Total reductions</td>
                    <td class="value negative">{{ qty_format($totalNegativeQty) }} units</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">Net adjustment value</td>
                    <td class="value {{ $totalValue >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($totalValue) }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.1cm;"></div>

        <!-- FOOTER WITH SIGNATURES & TIMESTAMPS -->
        <div class="footer-note clearfix">
            <div class="signature-box">
                <div class="signature-line">
                    Approved by / signature
                </div>
                <div style="margin-top: 10px; font-size:9pt; color:#4f6f84;">
                    Generated: {{ now()->format(app_date_time_format()) }}
                </div>
            </div>
            <div class="meta-box">
                <div class="adjustment-stamp">INVENTORY ADJ</div>
                <div style="margin-top: 10px; font-size:9pt;">
                    <strong>Ledger updated:</strong> {{ $record->updated_at->format(app_date_time_format()) }}
                </div>
            </div>
        </div>

        <!-- LEDGER REFERENCE -->
        <hr>
        <div style="text-align:center; color:#869fac; font-size:7pt;">
            inventory ledger updated • adjustment ref: {{ $record->adjustment_number }}
        </div>

    </div> <!-- end adjustment-wrapper -->

    <!-- BOTTOM SPACING -->
    <div style="margin-top:0.7cm; text-align:right; color:#92a9b9; font-size:8pt;">
        inventory adjustment document • physical count verification required
    </div>
</body>

</html>
