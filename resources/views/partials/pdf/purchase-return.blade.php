@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Return #{!! $record->return_number !!}</title>
    <style>
        /*-----------------------------------------------
            RESET & BASE STYLES
        -----------------------------------------------*/
        html,
        body {
            height: auto;
            overflow: visible;
            margin: 0;
            padding: 0;
        }

        /* ULTRA COMPACT - 2 returns per page */
        body {
            margin: 0;
            padding: 0.2cm;
            font-family: 'Trebuchet MS', 'Helvetica', 'Arial', sans-serif;
            font-size: 7.5pt;
            line-height: 1.25;
            color: #1e2b37;
            background: #fff;
            max-width: 100%;
        }

        /*-----------------------------------------------
            RETURN WRAPPER
        -----------------------------------------------*/
        .return-wrapper {
            position: relative;
            border: 0.8px solid #d9e0e6;
            border-bottom: 1.2px solid #cbd2d9;
            border-right: 1.2px solid #cbd2d9;
            background: #fff;
            padding: 0.2cm;
            margin-bottom: 0.2cm;
        }

        /*-----------------------------------------------
            CLEARFIX
        -----------------------------------------------*/
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

        /*-----------------------------------------------
            HEADER SECTION
        -----------------------------------------------*/
        .header-left {
            float: left;
            width: 49%;
            margin: 0 0 0.1cm;
        }

        .header-right {
            float: right;
            width: 49%;
            margin: 0 0 0.1cm;
            text-align: right;
        }

        .company-name {
            font-size: 16pt;
            font-weight: normal;
            letter-spacing: 0.3px;
            color: #0b1c26;
            margin: 0;
            padding: 0 0 1px;
            line-height: 1.15;
            border-bottom: 1.8px solid #c62828;
            /* Red for return */
            display: inline-block;
        }

        .company-detail {
            font-size: 6.5pt;
            color: #405b69;
            margin: 2px 0 0;
            line-height: 1.2;
        }

        /* Document meta */
        .doc-label {
            font-size: 10pt;
            font-weight: bold;
            color: #c62828;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin: 0;
        }

        .doc-number {
            font-size: 12pt;
            color: #1e2b37;
            margin: 0;
            font-weight: bold;
            border-bottom: 1.8px solid #c62828;
            padding-bottom: 1px;
            display: inline-block;
        }

        .doc-meta {
            font-size: 6.5pt;
            color: #5b7482;
            margin-top: 0.06cm;
            line-height: 1.2;
        }

        /*-----------------------------------------------
            RETURN TYPE BADGE
        -----------------------------------------------*/
        .return-type-card {
            width: 100%;
            margin: 0.1cm 0 0.15cm;
            border-collapse: collapse;
        }

        .return-type-card td {
            vertical-align: middle;
            background: #fff1f0;
            border: 0.8px solid #ef9a9a;
            padding: 0.1cm;
        }

        .type-badge {
            background: #c62828;
            color: white;
            padding: 3px 12px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }

        .type-label {
            font-size: 8pt;
            color: #c62828;
            font-weight: bold;
            margin-left: 10px;
        }

        /*-----------------------------------------------
            PARTY GRID - 2 Columns for Return
        -----------------------------------------------*/
        .party-grid {
            width: 100%;
            margin: 0.1cm 0 0.15cm;
            border-collapse: collapse;
        }

        .party-grid td {
            width: 50%;
            vertical-align: top;
            background: #f6f9fc;
            border: 0.8px solid #dde5ec;
            padding: 0.15cm;
        }

        .party-grid td.left-cell {
            border-right: none;
        }

        .party-title {
            font-size: 8pt;
            font-weight: bold;
            color: #1c3b4f;
            margin: 0 0 0.06cm;
            text-transform: uppercase;
            border-bottom: 1.5px solid #8ba0ae;
            padding-bottom: 1px;
            display: inline-block;
        }

        .party-detail {
            font-size: 7pt;
            color: #2a3f4d;
            line-height: 1.2;
            margin: 0.06cm 0 0;
        }

        .party-detail strong {
            color: #0f2938;
            font-size: 7.5pt;
        }

        /* Purchase Reference Full Width */
        .reference-row {
            width: 100%;
            margin: 0.1cm 0 0.15cm;
            background: #f6f9fc;
            border: 0.8px solid #dde5ec;
            padding: 0.15cm;
        }

        /*-----------------------------------------------
            DESCRIPTION CARD
        -----------------------------------------------*/
        .desc-card {
            background: #fff1f0;
            border: 0.8px solid #ef9a9a;
            padding: 0.15cm;
            margin: 0.15cm 0;
        }

        .desc-label {
            font-size: 7pt;
            font-weight: bold;
            color: #c62828;
            text-transform: uppercase;
            margin: 0 0 1.5px;
        }

        .desc-text {
            font-size: 7pt;
            color: #2b4b5e;
            font-style: italic;
            line-height: 1.2;
        }

        /*-----------------------------------------------
            SUMMARY INFO ROW
        -----------------------------------------------*/
        .summary-info {
            width: 100%;
            margin: 0.06cm 0;
            font-size: 6.5pt;
            color: #1e2b37;
        }

        .summary-info .info-box {
            background: #fff1f0;
            border: 0.8px solid #ef9a9a;
            padding: 1.5px 6px;
            display: inline-block;
        }

        .summary-info strong {
            color: #c62828;
        }

        /*-----------------------------------------------
            ITEMS TABLE
        -----------------------------------------------*/
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.15cm 0;
            font-size: 7pt;
            table-layout: fixed;
        }

        .items-table th {
            background: #c62828;
            color: #fff;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 6.5pt;
            padding: 4px;
            border: 0.8px solid #8b1e1e;
            text-align: left;
        }

        .items-table td {
            padding: 4px;
            border: 0.8px solid #cddae3;
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

        .negative {
            color: #c62828;
        }

        .product-sku {
            font-size: 5.5pt;
            color: #6f8a9c;
            display: block;
            line-height: 1.1;
            margin-top: 1px;
        }

        /*-----------------------------------------------
            SUMMARY PANEL
        -----------------------------------------------*/
        .summary-panel {
            float: right;
            width: 48%;
            margin: 0.1cm 0;
            border-collapse: collapse;
        }

        .summary-panel td {
            padding: 4px 6px;
            border: 0.8px solid #ccdae5;
            font-size: 7pt;
        }

        .summary-panel .label {
            background: #eef3f8;
            font-weight: bold;
            color: #1f3f52;
            text-align: left;
            width: 60%;
        }

        .summary-panel .value {
            text-align: right;
            background: #fff;
            font-weight: bold;
        }

        .summary-panel .total-row td {
            background: #ffebee;
            font-size: 8.5pt;
            font-weight: bold;
            color: #c62828;
            border: 0.8px solid #ef9a9a;
            padding: 5px;
        }

        .positive {
            color: #2e7d32;
        }

        .negative {
            color: #c62828;
        }

        /*-----------------------------------------------
            FOOTER
        -----------------------------------------------*/
        .footer-note {
            width: 100%;
            margin-top: 0.2cm;
            border-top: 2px solid #c62828;
            padding-top: 0.1cm;
            overflow: hidden;
        }

        .footer-left {
            float: left;
            width: 33%;
            text-align: left;
        }

        .footer-center {
            float: left;
            width: 34%;
            text-align: center;
        }

        .footer-right {
            float: right;
            width: 33%;
            text-align: right;
        }

        .disclaimer-text {
            font-size: 6pt;
            color: #6f8a9c;
            font-style: italic;
        }

        .return-stamp {
            background: #fff1f0;
            padding: 3px 10px;
            border: 1.5px dashed #c62828;
            display: inline-block;
            font-size: 9pt;
            font-weight: bold;
            color: #c62828;
            letter-spacing: 1.5px;
            opacity: 0.9;
            text-transform: uppercase;
            transform: rotate(-1deg);
        }

        hr {
            border: 0;
            border-top: 1.2px solid #ef9a9a;
            margin: 0.1cm 0;
        }

        /*-----------------------------------------------
            WATERMARK
        -----------------------------------------------*/
        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-align: center;
            opacity: 0.06;
            padding-top: 3.8cm;
            pointer-events: none;
            z-index: 1;
        }

        .watermark img {
            max-width: 60%;
            max-height: 5cm;
            object-fit: contain;
        }

        /*-----------------------------------------------
            DIVIDER LINE
        -----------------------------------------------*/
        .divider {
            border-top: 1px dashed #ef9a9a;
            margin: 0.15cm 0;
        }

        /*-----------------------------------------------
            OVERRIDES & PAGE SETTINGS
        -----------------------------------------------*/
        div[style*="margin-top:0.7cm"] {
            margin-top: 0.15cm !important;
        }

        div[style*="height:0.1cm"] {
            height: 0.04cm !important;
        }

        @page {
            size: A4;
            margin: 0.4cm;
        }
    </style>
</head>

<body>

    @php
        // Helper function to get image path
        $getImagePath = function ($setting) use ($generalSettings) {
            $image = $generalSettings->$setting ?? '';
            if (!$image) {
                return null;
            }

            $url = filter_var($image, FILTER_VALIDATE_URL)
                ? $image
                : config('app.url') . '/storage/' . ltrim($image, '/');

            $path = public_path(str_replace(config('app.url'), '', $url));
            return file_exists($path) ? $path : null;
        };

        $watermarkPath = $getImagePath('invoice_watermark_logo');
        $logoPath = $getImagePath('site_logo');
        $footerLogoPath = $getImagePath('invoice_footer_logo');

        $grandTotal = $record->grand_total ?? $record->items->sum('total');
        $previousBalance = $record->purchase->supplier->getSupplierBalanceAsOf($record->created_at);
        $updatedBalance = $previousBalance - $grandTotal;
        $totalItems = $record->items->count();
        $totalQty = $record->items->sum('qty');
    @endphp

    <!-- First Return - Office Copy -->
    <div class="return-wrapper">
        @if ($watermarkPath)
            <div class="watermark">
                <img src="{{ $watermarkPath }}" alt="Watermark">
            </div>
        @endif

        <!-- HEADER -->
        <div class="clearfix">
            <div class="header-left">
                @if ($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $generalSettings->site_name ?? 'Logo' }}"
                        style="max-height: 1.8cm; max-width: 5.5cm; margin-bottom: 0.06cm; display: block;">
                @else
                    <div class="company-name">{{ $generalSettings->site_name ?? 'My App' }}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="doc-label">purchase return</div>
                <div class="doc-number">{{ $record->return_number }}</div>
                <div class="doc-meta">
                    Created at: {{ $record->created_at->format(app_date_time_format()) }} | By:
                    {{ $record->creator->name }}
                </div>
            </div>
        </div>

        <!-- Supplier and Outlet Details - 2 Column Grid -->
        <table class="party-grid" cellspacing="0">
            <tr>
                <td class="left-cell">
                    <div class="party-title">supplier</div>
                    <div class="party-detail">
                        <strong>{{ $record->purchase->supplier->name }}</strong><br>
                        {{ $record->purchase->supplier->address ?? '' }}
                        @if ($record->purchase->supplier->contact)
                            <br>Tel: {{ $record->purchase->supplier->contact }}
                        @endif
                        @if ($record->purchase->supplier->email)
                            <br>{{ $record->purchase->supplier->email }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="party-title">outlet</div>
                    <div class="party-detail">
                        <strong>{{ $record->outlet->name }}</strong><br>
                        {{ $record->outlet->address ?? '' }}
                        @if ($record->outlet->phone_number)
                            <br>Tel: {{ $record->outlet->phone_number }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Original Purchase Reference -->
        <div class="reference-row">
            <div class="party-title">original purchase</div>
            <div class="party-detail">
                <strong>PO: {{ $record->purchase->purchase_number }}</strong> |
                Date: {{ $record->purchase->created_at->format(app_date_time_format()) }} |
                Amount: {{ currency_format($record->purchase->grand_total) }}
            </div>
        </div>

        <!-- Description -->
        @if ($record->description)
            <div class="desc-card">
                <div class="desc-label">return reason</div>
                <div class="desc-text">{{ Str::limit($record->description, 90) }}</div>
            </div>
        @endif

        <!-- Summary Info -->
        <div class="summary-info clearfix">
            <div class="info-box">
                <strong>Items:</strong> {{ $totalItems }} | <strong>Qty:</strong> {{ qty_format($totalQty) }}
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table" cellspacing="0">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="12%">Qty</th>
                    <th width="48%">Product</th>
                    <th width="13%">Rate</th>
                    <th width="11%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->items as $index => $item)
                    @php
                        $productDetails = collect([$item->product->brand?->name, $item->product->category?->name])
                            ->filter()
                            ->map(fn($n) => "- $n")
                            ->join(' ');
                    @endphp
                    <tr @if ($index % 2 == 1) class="alt" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td class="qty-cell negative">{{ qty_format($item->qty) }} {{ $item->product->unit->symbol }}
                        </td>
                        <td>
                            {{ $item->product->name }} {{ $productDetails }}
                        </td>
                        <td class="rate-cell">{{ currency_format($item->rate) }}</td>
                        <td class="total-cell negative">{{ currency_format($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Totals -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>
                <tr>
                    <td class="label">Return amount</td>
                    <td class="value negative">-{{ currency_format($grandTotal) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">New balance</td>
                    <td class="value {{ $updatedBalance >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($updatedBalance) }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.06cm;"></div>

        <!-- Footer - Office Copy -->
        <div class="footer-note clearfix">
            <div class="footer-left"><span class="disclaimer-text">Computer generated</span></div>
            @if ($footerLogoPath)
                <div class="footer-center"><img style="max-height: 0.7cm;" src="{{ $footerLogoPath }}" alt="Footer">
                </div>
            @endif
            <div class="footer-right"><span class="disclaimer-text">No signature required</span></div>
        </div>

        @if (config('software.marketing_footer_enabled', false))
            <!-- Solo Dev Marketing -->
            <div
                style="text-align:center; color:#6f8a9c; font-size:5.5pt; margin-top:0.05cm; border-top:0.5px dotted #ccdae5; padding-top:0.05cm;">

                <span>
                    {{ config('software.marketing_headline') }}
                    <strong>{{ config('software.developer_name') }}</strong>
                </span>
                <br>

                <span style="font-size:5pt;">
                    {{ collect([
                        config('software.developer_contact'),
                        config('software.developer_email'),
                        config('software.developer_portfolio'),
                    ])->filter()->join(' | ') }}
                </span>

            </div>
        @endif
    </div>

    <!-- Second Return - Supplier Copy -->
    <div class="return-wrapper">
        @if ($watermarkPath)
            <div class="watermark">
                <img src="{{ $watermarkPath }}" alt="Watermark">
            </div>
        @endif

        <!-- HEADER -->
        <div class="clearfix">
            <div class="header-left">
                @if ($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $generalSettings->site_name ?? 'Logo' }}"
                        style="max-height: 1.8cm; max-width: 5.5cm; margin-bottom: 0.06cm; display: block;">
                @else
                    <div class="company-name">{{ $generalSettings->site_name ?? 'My App' }}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="doc-label">purchase return</div>
                <div class="doc-number">{{ $record->return_number }}</div>
                <div class="doc-meta">
                    Created at: {{ $record->created_at->format(app_date_time_format()) }} | By:
                    {{ $record->creator->name }}
                </div>
            </div>
        </div>

        <!-- Supplier and Outlet Details -->
        <table class="party-grid" cellspacing="0">
            <tr>
                <td class="left-cell">
                    <div class="party-title">supplier</div>
                    <div class="party-detail">
                        <strong>{{ $record->purchase->supplier->name }}</strong><br>
                        {{ $record->purchase->supplier->address ?? '' }}
                        @if ($record->purchase->supplier->contact)
                            <br>Tel: {{ $record->purchase->supplier->contact }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="party-title">outlet</div>
                    <div class="party-detail">
                        <strong>{{ $record->outlet->name }}</strong><br>
                        {{ $record->outlet->address ?? '' }}
                        @if ($record->outlet->phone_number)
                            <br>Tel: {{ $record->outlet->phone_number }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Original Purchase Reference -->
        <div class="reference-row">
            <div class="party-title">original purchase</div>
            <div class="party-detail">
                <strong>PO: {{ $record->purchase->purchase_number }}</strong> |
                Date: {{ $record->purchase->created_at->format(app_date_time_format()) }}
            </div>
        </div>

        <!-- Description -->
        @if ($record->description)
            <div class="desc-card">
                <div class="desc-label">return reason</div>
                <div class="desc-text">{{ Str::limit($record->description, 90) }}</div>
            </div>
        @endif

        <!-- Summary Info -->
        <div class="summary-info clearfix">
            <div class="info-box">
                <strong>Items:</strong> {{ $totalItems }} | <strong>Qty:</strong> {{ qty_format($totalQty) }}
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table" cellspacing="0">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="12%">Qty</th>
                    <th width="48%">Product</th>
                    <th width="13%">Rate</th>
                    <th width="11%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->items as $index => $item)
                    @php
                        $productDetails = collect([$item->product->brand?->name, $item->product->category?->name])
                            ->filter()
                            ->map(fn($n) => "- $n")
                            ->join(' ');
                    @endphp
                    <tr @if ($index % 2 == 1) class="alt" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td class="qty-cell negative">{{ qty_format($item->qty) }} {{ $item->product->unit->symbol }}
                        </td>
                        <td>
                            {{ $item->product->name }} {{ $productDetails }}
                        </td>
                        <td class="rate-cell">{{ currency_format($item->rate) }}</td>
                        <td class="total-cell negative">{{ currency_format($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Totals -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>
                <tr>
                    <td class="label">Return amount</td>
                    <td class="value negative">-{{ currency_format($grandTotal) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">New balance</td>
                    <td class="value {{ $updatedBalance >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($updatedBalance) }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.06cm;"></div>

        <!-- Footer - Supplier Copy with Return Stamp -->
        <div class="footer-note clearfix">
            <div class="footer-left"><span class="disclaimer-text">Computer generated</span></div>
            @if ($footerLogoPath)
                <div class="footer-center"><img style="max-height: 0.7cm;" src="{{ $footerLogoPath }}"
                        alt="Footer"></div>
            @endif
            <div class="footer-right"><span class="return-stamp">Signature</span></div>
        </div>

        @if (config('software.marketing_footer_enabled', false))
            <!-- Solo Dev Marketing -->
            <div
                style="text-align:center; color:#6f8a9c; font-size:5.5pt; margin-top:0.05cm; border-top:0.5px dotted #ccdae5; padding-top:0.05cm;">

                <span>
                    {{ config('software.marketing_headline') }}
                    <strong>{{ config('software.developer_name') }}</strong>
                </span>
                <br>

                <span style="font-size:5pt;">
                    {{ collect([
                        config('software.developer_contact'),
                        config('software.developer_email'),
                        config('software.developer_portfolio'),
                    ])->filter()->join(' | ') }}
                </span>

            </div>
        @endif
    </div>
</body>

</html>
