@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Stock Transfer #{!! $record->transfer_number !!}</title>
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
            /* increased from 0.5cm (+40%) */
            font-family: 'Trebuchet MS', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            /* increased from 9pt (+11%) */
            line-height: 1.6;
            /* increased from 1.5 (+7%) */
            color: #1e2b37;
            background: #ffffff;
            max-width: 100%;
        }

        .transfer-wrapper {
            border: 1.5px solid #d9e0e6;
            /* thicker */
            background: #ffffff;
            padding: 0.7cm;
            /* increased from 0.5cm (+40%) */
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
            /* increased from 0.35cm (+43%) */
        }

        .header-right {
            float: right;
            width: 49%;
            margin: 0 0 0.5cm 0;
            /* increased from 0.35cm (+43%) */
            text-align: right;
        }

        .company-name {
            font-size: 26pt;
            /* increased from 22pt (+18%) */
            font-weight: normal;
            letter-spacing: 1px;
            color: #0b1c26;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            border-bottom: 2.5px solid #728f9b;
            /* thicker */
            display: inline-block;
            padding-bottom: 4px;
            /* increased from 3px */
        }

        .company-detail {
            font-size: 9.5pt;
            /* increased from 8.5pt (+12%) */
            color: #405b69;
            margin: 6px 0 0 0;
            /* increased from 4px (+50%) */
            line-height: 1.5;
        }

        /* document meta */
        .doc-label {
            font-size: 15pt;
            /* increased from 13pt (+15%) */
            font-weight: bold;
            color: #2c4556;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0 0 2px 0;
            /* added bottom margin */
        }

        .doc-number {
            font-size: 19pt;
            /* increased from 16pt (+19%) */
            color: #1e2b37;
            margin: 0;
            font-weight: bold;
            border-bottom: 2.5px solid #2c4556;
            padding-bottom: 4px;
            /* increased from 3px */
            display: inline-block;
        }

        .doc-meta {
            font-size: 9.5pt;
            /* increased from 8.5pt (+12%) */
            color: #5b7482;
            margin-top: 0.2cm;
            /* increased from 0.15cm (+33%) */
            line-height: 1.5;
        }

        /* OUTLET CARDS - ultra spacious */
        .transfer-flow {
            width: 100%;
            margin: 0.5cm 0;
            /* increased from 0.35cm (+43%) */
            border-collapse: collapse;
        }

        .transfer-flow td {
            width: 45%;
            vertical-align: top;
            background: #f6f9fc;
            border: 1.5px solid #dde5ec;
            /* thicker */
            padding: 0.5cm;
            /* increased from 0.35cm (+43%) */
        }

        .transfer-flow td.arrow-cell {
            width: 10%;
            background: transparent;
            border: none;
            text-align: center;
            vertical-align: middle;
            font-size: 22pt;
            /* increased from 18pt (+22%) */
            color: #2c4556;
            font-weight: bold;
            padding: 0;
        }

        .outlet-title {
            font-size: 14pt;
            /* increased from 12pt (+17%) */
            font-weight: bold;
            color: #1c3b4f;
            margin: 0 0 0.2cm 0;
            /* increased from 0.15cm (+33%) */
            text-transform: uppercase;
            border-bottom: 2.5px solid #8ba0ae;
            padding-bottom: 4px;
            /* increased from 3px */
            display: inline-block;
        }

        .outlet-detail {
            font-size: 10pt;
            /* increased from 9pt (+11%) */
            color: #2a3f4d;
            line-height: 1.5;
            margin: 0.2cm 0 0 0;
            /* increased from 0.15cm (+33%) */
        }

        .outlet-detail strong {
            color: #0f2938;
            font-size: 10.5pt;
            /* increased from 9.5pt (+10%) */
        }

        .badge {
            background: #2c4556;
            color: white;
            padding: 4px 10px;
            /* increased from 3px 8px */
            font-size: 8.5pt;
            /* increased from 7.5pt (+13%) */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 8px;
            /* increased from 6px (+33%) */
        }

        /* description card - ultra breathing */
        .desc-card {
            background: #f0f5fa;
            border: 1.5px solid #c5d5e2;
            /* thicker */
            padding: 0.5cm;
            /* increased from 0.35cm (+43%) */
            margin: 0.5cm 0;
            /* increased from 0.35cm (+43%) */
        }

        .desc-label {
            font-size: 11pt;
            /* increased from 9.5pt (+16%) */
            font-weight: bold;
            color: #1f3f52;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            /* increased from 4px (+50%) */
        }

        .desc-text {
            font-size: 10pt;
            /* increased from 9pt (+11%) */
            color: #2b4b5e;
            font-style: italic;
            line-height: 1.5;
        }

        /* ITEMS TABLE - premium spacing */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5cm 0;
            /* increased from 0.35cm (+43%) */
            font-size: 10pt;
            /* increased from 9pt (+11%) */
            table-layout: fixed;
        }

        .items-table th {
            background: #2c4556;
            color: white;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-size: 9.5pt;
            /* increased from 8.5pt (+12%) */
            padding: 10px;
            /* increased from 8px (+25%) */
            border: 1.5px solid #1f3542;
            /* thicker */
            text-align: left;
        }

        .items-table td {
            padding: 10px;
            /* increased from 8px (+25%) */
            border: 1.5px solid #cddae3;
            /* thicker */
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

        .product-sku {
            font-size: 8pt;
            /* increased from 7pt (+14%) */
            color: #6f8a9c;
            display: block;
            line-height: 1.4;
            margin-top: 3px;
            /* increased from 2px */
        }

        /* summary panel - ultra breathing */
        .summary-panel {
            float: right;
            width: 45%;
            margin: 0.3cm 0;
            /* increased from 0.2cm (+50%) */
            border-collapse: collapse;
        }

        .summary-panel td {
            padding: 8px 10px;
            /* increased from 6px 8px (+25-33%) */
            border: 1.5px solid #ccdae5;
            /* thicker */
            font-size: 10pt;
            /* increased from 9pt (+11%) */
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
            /* increased from 11pt (+18%) */
            font-weight: bold;
            color: #0d2635;
            border: 1.5px solid #9fb3c2;
            padding: 10px;
            /* increased from 8px (+25%) */
        }

        /* footer - ultra spaced */
        .footer-note {
            width: 100%;
            margin-top: 0.6cm;
            /* increased from 0.4cm (+50%) */
            border-top: 2.5px solid #2c4556;
            /* thicker */
            padding-top: 0.5cm;
            /* increased from 0.35cm (+43%) */
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
            /* thicker */
            width: 160px;
            /* increased from 140px (+14%) */
            margin-top: 18px;
            /* increased from 15px (+20%) */
            padding-top: 5px;
            /* increased from 4px (+25%) */
            font-size: 8.5pt;
            /* increased from 7.5pt (+13%) */
            color: #4a6a7c;
        }

        .transfer-stamp {
            background: #f0f5fa;
            padding: 7px 15px;
            /* increased from 5px 12px (+25-40%) */
            border: 2px dashed #2c4556;
            /* thicker */
            display: inline-block;
            font-size: 17pt;
            /* increased from 14pt (+21%) */
            font-weight: bold;
            color: #2c4556;
            letter-spacing: 3px;
            opacity: 0.8;
        }

        hr {
            border: 0;
            border-top: 2px solid #c0d2df;
            /* thicker */
            margin: 0.3cm 0;
            /* increased from 0.2cm (+50%) */
        }

        /* override inline styles - increased ultra */
        div[style*="margin-top:0.7cm"] {
            margin-top: 0.25cm !important;
            /* increased from 0.2cm (+25%) */
        }

        div[style*="height:0.1cm"] {
            height: 0.09cm !important;
            /* increased from 0.07cm (+28%) */
        }

        /* keep single page with ultra breathing */
        * {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="transfer-wrapper">

        <!-- HEADER -->
        <div class="clearfix">
            <div class="header-left">
                <div class="company-name">{{ $generalSettings->site_name ?? 'My App' }}</div>
                <div class="company-detail">
                    {{-- {!! $generalSettings->address ?? 'Karachi' !!}<br>
                    {!! $generalSettings->contact ?? '03154573767' !!} --}}
                </div>
            </div>
            <div class="header-right">
                <div class="doc-label">stock transfer</div>
                <div class="doc-number">{!! $record->transfer_number !!}</div>
                <div class="doc-meta">
                    Created: {!! $record->created_at->format(app_date_time_format()) !!}<br>
                    @if ($record->created_at != $record->updated_at)
                        updated: {!! $record->updated_at->format(app_date_time_format()) !!}
                    @endif
                </div>
            </div>
        </div>

        <!-- TRANSFER FLOW (FROM → TO) -->
        <table class="transfer-flow" cellspacing="0">
            <tr>
                <td>
                    <div class="outlet-title">from outlet</div>
                    <div class="outlet-detail">
                        <strong>{!! $record->fromOutlet->name !!}</strong><br>
                        {!! $record->fromOutlet->address ?? 'Address not available' !!}<br>
                        @if ($record->fromOutlet->phone_number)
                            tel: {!! $record->fromOutlet->phone_number !!}
                        @endif
                    </div>
                    <div class="badge">origin</div>
                </td>
                <td class="arrow-cell">To</td>
                <td>
                    <div class="outlet-title">to outlet</div>
                    <div class="outlet-detail">
                        <strong>{!! $record->toOutlet->name !!}</strong><br>
                        {!! $record->toOutlet->address ?? 'Address not available' !!}<br>
                        @if ($record->toOutlet->phone)
                            tel: {!! $record->toOutlet->phone !!}
                        @endif
                    </div>
                    <div class="badge" style="background:#4f6f84;">destination</div>
                </td>
            </tr>
        </table>

        <!-- DESCRIPTION / NOTES -->
        @if ($record->description)
            <div class="desc-card">
                <div class="desc-label">transfer notes</div>
                <div class="desc-text">{!! nl2br(e($record->description)) !!}</div>
            </div>
        @endif

        <!-- ITEMS TABLE -->
        <table class="items-table" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">product</th>
                    {{-- <th width="12%">sku</th> --}}
                    <th width="12%">qty</th>
                    <th width="13%">rate</th>
                    <th width="13%">value</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalValue = 0;
                    $totalQty = 0;
                @endphp
                @foreach ($record->items as $index => $item)
                    @php
                        $avgRate = $item->product->getAvgRateAsOf($item->created_at) ?: $item->product->cost_price;
                        $value = $item->qty * $avgRate;
                        $totalValue += $value;
                        $totalQty += $item->qty;
                    @endphp
                    <tr @if ($index % 2 == 1) class="alt" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if ($item->product->description)
                                <span class="product-sku">{{ Str::limit($item->product->description, 40) }}</span>
                            @endif
                        </td>
                        {{-- <td>{{ $item->product->sku ?? '—' }}</td> --}}
                        <td class="qty-cell">{{ qty_format($item->qty) . ' ' . $item->product->unit->symbol }}</td>
                        <td class="qty-cell">{{ currency_format($avgRate) }}</td>
                        <td class="value-cell">{{ currency_format($value) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY TOTALS -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">total items</td>
                    <td class="value">{{ $record->items->count() }}</td>
                </tr>
                <tr>
                    <td class="label">total quantity</td>
                    <td class="value">{{ $totalQty }} units</td>
                </tr>
                <tr class="total-row">
                    <td class="label">total transfer value</td>
                    <td class="value">{{ currency_format($totalValue) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.1cm;"></div>

        <!-- FOOTER WITH SIGNATURES & TIMESTAMPS -->
        <div class="footer-note clearfix">
            <div class="signature-box">
                <div class="signature-line">
                    Issued by / signature
                </div>
                <div style="margin-top: 10px; font-size:9pt; color:#4f6f84;">
                    Generated: {{ now()->format(app_date_time_format()) }}
                </div>
            </div>
            <div class="meta-box">
                <div class="transfer-stamp">STOCK TRANSFER</div>
                <div style="margin-top: 10px; font-size:9pt;">
                    <strong>Inventory updated:</strong> {{ $record->updated_at->format(app_date_time_format()) }}
                </div>
            </div>
        </div>

        <!-- LEDGER REFERENCE (optional micro print) -->
        <hr>
        <div style="text-align:center; color:#869fac; font-size:7pt;">
            ledger entries created for both outlets • transaction ref: {{ $record->transfer_number }}
        </div>

    </div> <!-- end transfer-wrapper -->

    <!-- BOTTOM SPACING -->
    <div style="margin-top:0.7cm; text-align:right; color:#92a9b9; font-size:8pt;">
        stock transfer document • valid upon matching signatures
    </div>
</body>

</html>
