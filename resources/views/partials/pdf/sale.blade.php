@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sale Invoice #{!! $record->sale_number !!}</title>
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

        .sale-wrapper {
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
            border-bottom: 2.5px solid #2e7d32;
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
            color: #2e7d32;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0 0 2px 0;
        }

        .doc-number {
            font-size: 16pt;
            color: #1e2b37;
            margin: 0;
            font-weight: bold;
            border-bottom: 2.5px solid #2e7d32;
            padding-bottom: 4px;
            display: inline-block;
        }

        .doc-meta {
            font-size: 9.5pt;
            color: #5b7482;
            margin-top: 0.2cm;
            line-height: 1.5;
        }

        /* SALE TYPE BADGE */
        .sale-type-card {
            width: 100%;
            margin: 0.3cm 0 0.5cm 0;
            border-collapse: collapse;
        }

        .sale-type-card td {
            vertical-align: middle;
            background: #e8f5e9;
            border: 1.5px solid #a5d6a7;
            padding: 0.4cm;
        }

        .type-badge {
            background: #2e7d32;
            color: white;
            padding: 6px 15px;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .type-label {
            font-size: 12pt;
            color: #2e7d32;
            font-weight: bold;
            margin-left: 15px;
        }

        /* PARTY CARDS - Customer and Outlet */
        .party-section {
            width: 100%;
            margin: 0.3cm 0 0.5cm 0;
            border-collapse: collapse;
        }

        .party-section td {
            width: 48%;
            vertical-align: top;
            background: #f6f9fc;
            border: 1.5px solid #dde5ec;
            padding: 0.5cm;
        }

        .party-section td.left-cell {
            margin-right: 2%;
        }

        .party-section td.right-cell {
            margin-left: 2%;
        }

        .party-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1c3b4f;
            margin: 0 0 0.2cm 0;
            text-transform: uppercase;
            border-bottom: 2.5px solid #8ba0ae;
            padding-bottom: 4px;
            display: inline-block;
        }

        .party-detail {
            font-size: 11pt;
            color: #2a3f4d;
            line-height: 1.5;
            margin: 0.2cm 0 0 0;
        }

        .party-detail strong {
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

        .badge-customer {
            background: #2e7d32;
        }

        /* description card - ultra breathing */
        .desc-card {
            background: #e8f5e9;
            border: 1.5px solid #a5d6a7;
            padding: 0.5cm;
            margin: 0.5cm 0;
        }

        .desc-label {
            font-size: 11pt;
            font-weight: bold;
            color: #2e7d32;
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
            background: #2e7d32;
            color: white;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-size: 9.5pt;
            padding: 10px;
            border: 1.5px solid #1b5e20;
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
        .rate-cell,
        .total-cell {
            text-align: right;
            font-weight: bold;
        }

        .product-sku {
            font-size: 8pt;
            color: #6f8a9c;
            display: block;
            line-height: 1.4;
            margin-top: 3px;
        }

        /* discount row styling */
        .discount-info {
            font-size: 9pt;
            color: #2e7d32;
            display: block;
            margin-top: 2px;
        }

        .discount-amount {
            font-size: 9pt;
            color: #c62828;
            display: block;
            margin-top: 2px;
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
            background: #e8f5e9;
            font-size: 13pt;
            font-weight: bold;
            color: #2e7d32;
            border: 1.5px solid #a5d6a7;
            padding: 10px;
        }

        .positive {
            color: #2e7d32;
        }

        .negative {
            color: #c62828;
        }

        /* footer - ultra spaced */
        .footer-note {
            width: 100%;
            margin-top: 0.6cm;
            border-top: 2.5px solid #2e7d32;
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
            border-top: 2px solid #2e7d32;
            width: 160px;
            margin-top: 18px;
            padding-top: 5px;
            font-size: 8.5pt;
            color: #4a6a7c;
        }

        .sale-stamp {
            background: #e8f5e9;
            padding: 7px 15px;
            border: 2px dashed #2e7d32;
            display: inline-block;
            font-size: 17pt;
            font-weight: bold;
            color: #2e7d32;
            letter-spacing: 3px;
            opacity: 0.8;
        }

        hr {
            border: 0;
            border-top: 2px solid #a5d6a7;
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

    <div class="sale-wrapper">

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
                <div class="doc-label">sale invoice</div>
                <div class="doc-number">{!! $record->sale_number !!}</div>
                <div class="doc-meta">
                    Date: {!! $record->created_at->format(app_date_time_format()) !!}<br>
                    @if ($record->created_at != $record->updated_at)
                        Last updated: {!! $record->updated_at->format(app_date_time_format()) !!}
                    @endif
                </div>
            </div>
        </div>

        <!-- SALE TYPE -->
        <table class="sale-type-card" cellspacing="0">
            <tr>
                <td>
                    <span class="type-badge">SALE</span>
                    <span class="type-label">invoice</span>
                </td>
            </tr>
        </table>

        <!-- Customer and Outlet Details Side by Side -->
        <table class="party-section" cellspacing="0">
            <tr>
                <td class="left-cell">
                    <div class="party-title">customer</div>
                    <div class="party-detail">
                        <strong>{!! $record->customer->name !!}</strong><br>
                        {!! $record->customer->address ?? 'Address not available' !!}<br>
                        @if ($record->customer->contact)
                            tel: {!! $record->customer->contact !!}<br>
                        @endif
                        @if ($record->customer->city)
                            city: {!! $record->customer->city->name !!}
                        @endif
                    </div>
                    <div class="badge badge-customer">bill to</div>
                </td>
                <td class="right-cell">
                    <div class="party-title">outlet</div>
                    <div class="party-detail">
                        <strong>{!! $record->outlet->name !!}</strong><br>
                        {!! $record->outlet->address ?? 'Address not available' !!}<br>
                        @if ($record->outlet->phone_number)
                            tel: {!! $record->outlet->phone_number !!}
                        @endif
                    </div>
                    <div class="badge">sold from</div>
                </td>
            </tr>
        </table>

        <!-- DESCRIPTION / NOTES -->
        @if ($record->description)
            <div class="desc-card">
                <div class="desc-label">sale notes</div>
                <div class="desc-text">{!! nl2br(e($record->description)) !!}</div>
            </div>
        @endif

        <!-- ITEMS TABLE -->
        <table class="items-table" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="38%">product</th>
                    <th width="12%">qty</th>
                    <th width="12%">unit price</th>
                    {{-- <th width="13%">discount</th> --}}
                    <th width="10%">total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                @endphp
                @foreach ($record->items as $index => $item)
                    @php
                        $subtotal += $item->total;

                        // Calculate original total based on selling price
                        $sellingPrice = $item->rate ?? 0;
                        $originalTotal = $sellingPrice * $item->qty;
                        $discountAmount = $originalTotal - $item->total;

                        // Format discount display
                        $discountDisplay = '';
                        if ($discountAmount > 0) {
                            $discountDisplay = '- ' . currency_format($discountAmount);
                        } elseif ($discountAmount < 0) {
                            $discountDisplay = '+ ' . currency_format(abs($discountAmount));
                        } else {
                            $discountDisplay = '—';
                        }
                    @endphp
                    <tr @if ($index % 2 == 1) class="alt" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if ($item->product->description)
                                <span class="product-sku">{{ Str::limit($item->product->description, 40) }}</span>
                            @endif
                            {{-- @if($item->unit && $item->unit_id != $item->product->unit_id)
                                <span class="product-sku">Unit: {{ $item->unit->name }}</span>
                            @endif --}}
                        </td>
                        <td class="qty-cell">{{ qty_format($item->qty) }} {{ $item->unit->symbol }}</td>
                        <td class="rate-cell">{{ currency_format($sellingPrice) }}</td>
                        {{-- <td class="rate-cell">
                            <span class="{{ $discountAmount > 0 ? 'discount-info' : ($discountAmount < 0 ? 'discount-amount' : '') }}">
                                {{ $discountDisplay }}
                            </span>
                        </td> --}}
                        <td class="total-cell">{{ currency_format($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY TOTALS with Discounts, Delivery, Tax -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">{{ currency_format($subtotal) }}</td>
                </tr>

                @if($record->discount_amount > 0)
                <tr>
                    <td class="label">
                        Additional Discount
                        @if($record->discount_type == \App\Enums\DiscountType::PERCENT)
                            ({{ $record->discount_value }}%)
                        @endif
                    </td>
                    <td class="value negative">- {{ currency_format($record->discount_amount) }}</td>
                </tr>
                @endif

                @if($record->delivery_charges > 0)
                <tr>
                    <td class="label">Delivery charges</td>
                    <td class="value">{{ currency_format($record->delivery_charges) }}</td>
                </tr>
                @endif

                @if($record->tax_charges > 0)
                <tr>
                    <td class="label">Tax charges</td>
                    <td class="value">{{ currency_format($record->tax_charges) }}</td>
                </tr>
                @endif

                @php
                    $previousBalance = $record->customer->getCustomerBalanceAsOf($record->created_at);
                    $updatedBalance = $previousBalance + $record->grand_total;
                @endphp

                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>

                <tr class="total-row">
                    <td class="label">Grand total</td>
                    <td class="value">{{ currency_format($record->grand_total) }}</td>
                </tr>

                <tr>
                    <td class="label">Updated balance</td>
                    <td class="value {{ $updatedBalance >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($updatedBalance) }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.1cm;"></div>

        <!-- FOOTER WITH SIGNATURES & TIMESTAMPS -->
        <div class="footer-note clearfix">
            <div class="signature-box">
                <div class="signature-line">
                    Authorized signature
                </div>
                <div style="margin-top: 10px; font-size:9pt; color:#4f6f84;">
                    Generated: {{ now()->format(app_date_time_format()) }}
                </div>
            </div>
            <div class="meta-box">
                <div class="sale-stamp">SALE INVOICE</div>
                <div style="margin-top: 10px; font-size:9pt;">
                    <strong>Customer ledger updated</strong>
                </div>
            </div>
        </div>

        <!-- LEDGER REFERENCE -->
        <hr>
        <div style="text-align:center; color:#869fac; font-size:7pt;">
            customer ledger updated • inventory ledger updated • sale ref: {{ $record->sale_number }}
        </div>

        <!-- AMOUNT IN WORDS -->
        <div style="text-align:center; color:#1e2b37; font-size:9pt; margin-top:0.3cm; font-style:italic;">
            <strong>Amount in words:</strong> {{ number_to_words_currency($record->grand_total) }}
        </div>

    </div> <!-- end sale-wrapper -->

    <!-- BOTTOM SPACING -->
    <div style="margin-top:0.7cm; text-align:right; color:#92a9b9; font-size:8pt;">
        sale invoice • goods once sold will not be taken back
    </div>
</body>

</html>
