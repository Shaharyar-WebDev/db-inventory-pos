@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt Voucher #{!! $record->receipt_number !!}</title>
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

        /* ULTRA COMPACT - 2 vouchers per page */
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
            RECEIPT WRAPPER - Enterprise Receipt Style
        -----------------------------------------------*/
        .receipt-wrapper {
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
            border-bottom: 1.8px solid #2c4556;
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
            color: #2c4556;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin: 0;
        }

        .doc-number {
            font-size: 12pt;
            color: #1e2b37;
            margin: 0;
            font-weight: bold;
            border-bottom: 1.8px solid #2c4556;
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
            PARTY GRID - 3 Columns
        -----------------------------------------------*/
        .party-grid {
            width: 100%;
            margin: 0.1cm 0 0.15cm;
            border-collapse: collapse;
        }

        .party-grid td {
            width: 33.33%;
            vertical-align: top;
            background: #f6f9fc;
            border: 0.8px solid #dde5ec;
            padding: 0.15cm;
        }

        .party-grid td.middle-cell {
            border-left: none;
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

        /*-----------------------------------------------
            AMOUNT CARD - Enterprise Highlight
        -----------------------------------------------*/
        .amount-card {
            width: 100%;
            margin: 0.15cm 0;
            border-collapse: collapse;
        }

        .amount-card td {
            vertical-align: middle;
            background: linear-gradient(to bottom, #f8fafc, #f0f5fa);
            border: 1px solid #c5d5e2;
            padding: 0.2cm;
            text-align: center;
        }

        .amount-label {
            font-size: 9pt;
            color: #1f3f52;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-right: 15px;
        }

        .amount-value {
            font-size: 18pt;
            font-weight: bold;
            color: {{ $record->amount < 0 ? '#c62828' : '#2e7d32' }};
            letter-spacing: 0.5px;
        }

        .payment-method {
            font-size: 8pt;
            color: #1f3f52;
            font-weight: bold;
            background: #e8f0fe;
            padding: 2px 8px;
            border-radius: 0;
            display: inline-block;
        }

        /*-----------------------------------------------
            DESCRIPTION CARD
        -----------------------------------------------*/
        .desc-card {
            background: #e8f5e9;
            border: 0.8px solid #a5d6a7;
            padding: 0.15cm;
            margin: 0.15cm 0;
        }

        .desc-label {
            font-size: 7pt;
            font-weight: bold;
            color: #2e7d32;
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
            background: #d9e2ec;
            font-size: 8.5pt;
            font-weight: bold;
            color: #0d2635;
            border: 0.8px solid #9fb3c2;
            padding: 5px;
        }

        .positive {
            color: #2e7d32;
        }

        .negative {
            color: #c62828;
        }

        /* Amount in words styling */
        .amount-words {
            font-size: 6pt;
            color: #4f6f84;
            font-style: italic;
            margin-top: 0.1cm;
            padding: 2px 0;
            border-top: 1px dotted #ccdae5;
        }

        /*-----------------------------------------------
            FOOTER - Enterprise Style
        -----------------------------------------------*/
        .footer-note {
            width: 100%;
            margin-top: 0.2cm;
            border-top: 2px solid #2c4556;
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

        .receipt-stamp {
            background: #f0f5fa;
            padding: 3px 10px;
            border: 1.5px dashed #2c4556;
            display: inline-block;
            font-size: 9pt;
            font-weight: bold;
            color: #2c4556;
            letter-spacing: 1.5px;
            opacity: 0.9;
            text-transform: uppercase;
            transform: rotate(-1deg);
        }

        hr {
            border: 0;
            border-top: 1.2px solid #c0d2df;
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
        .receipt-divider {
            border-top: 1px dashed #ccdae5;
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

        $previousBalance = $record->customer->getCustomerBalanceAsOf($record->created_at);
        $updatedBalance = $previousBalance - $record->amount;
        $paymentMethod = $record->paymentMethod->name ?? null;
    @endphp

    <!-- First Receipt - Office Copy -->
    <div class="receipt-wrapper">
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
                <div class="doc-label">receipt voucher</div>
                <div class="doc-number">{{ $record->receipt_number }}</div>
                <div class="doc-meta">
                    Created at: {{ $record->created_at->format(app_date_time_format()) }} | By:
                    {{ $record->creator->name }}
                </div>
            </div>
        </div>

        <!-- 3-Column Party Grid (Customer | Account | Outlet) -->
        <table class="party-grid" cellspacing="0">
            <tr>
                <td>
                    <div class="party-title">customer</div>
                    <div class="party-detail">
                        <strong>{{ $record->customer->name }}</strong><br>
                        {{ $record->customer->address ?? '' }}
                        @if ($record->customer->area || $record->customer->city)
                            <br>{{ $record->customer->area?->name }} {{ $record->customer->city?->name }}
                        @endif
                        @if ($record->customer->contact)
                            <br>Tel: {{ $record->customer->contact }}
                        @endif
                    </div>
                </td>
                <td class="middle-cell">
                    <div class="party-title">account</div>
                    <div class="party-detail">
                        <strong>{{ $record->account->name }}</strong><br>
                        {{ $record->account->account_number ?? 'N/A' }}
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

        <!-- Divider -->
        <div class="receipt-divider"></div>

        <!-- Amount Highlight with Payment Method -->
        <table class="amount-card" cellspacing="0">
            <tr>
                <td>
                    <span class="amount-value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format($record->amount) }}
                    </span>
                    @if ($paymentMethod)
                        <span class="payment-method">{{ $paymentMethod }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Amount in Words -->
        @if (function_exists('number_to_words_currency'))
            <div class="amount-words">
                <strong>Amount in words:</strong> {{ number_to_words_currency($record->amount) }}
            </div>
        @endif

        <!-- Remarks -->
        @if ($record->remarks)
            <div class="desc-card">
                <div class="desc-label">remarks</div>
                <div class="desc-text">{{ Str::limit($record->remarks, 90) }}</div>
            </div>
        @endif

        <!-- Summary Totals -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>
                <tr>
                    <td class="label">Receipt amount</td>
                    <td class="value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format($record->amount) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">New balance</td>
                    <td class="value {{ $updatedBalance >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($updatedBalance) }}</td>
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
    </div>

    <!-- Second Receipt - Customer Copy -->
    <div class="receipt-wrapper">
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
                <div class="doc-label">receipt voucher</div>
                <div class="doc-number">{{ $record->receipt_number }}</div>
                <div class="doc-meta">
                    Created at: {{ $record->created_at->format(app_date_time_format()) }} | By:
                    {{ $record->creator->name }}
                </div>
            </div>
        </div>

        <!-- 3-Column Party Grid -->
        <table class="party-grid" cellspacing="0">
            <tr>
                <td>
                    <div class="party-title">customer</div>
                    <div class="party-detail">
                        <strong>{{ $record->customer->name }}</strong><br>
                        {{ $record->customer->address ?? '' }}
                        @if ($record->customer->contact)
                            <br>Tel: {{ $record->customer->contact }}
                        @endif
                    </div>
                </td>
                <td class="middle-cell">
                    <div class="party-title">account</div>
                    <div class="party-detail">
                        <strong>{{ $record->account->name }}</strong><br>
                        {{ $record->account->account_number ?? 'N/A' }}
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

        <!-- Divider -->
        <div class="receipt-divider"></div>

        <!-- Amount Highlight with Payment Method -->
        <table class="amount-card" cellspacing="0">
            <tr>
                <td>
                    <span class="amount-value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format($record->amount) }}
                    </span>
                    @if ($paymentMethod)
                        <span class="payment-method">{{ $paymentMethod }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Amount in Words -->
        @if (function_exists('number_to_words_currency'))
            <div class="amount-words">
                <strong>Amount in words:</strong> {{ number_to_words_currency($record->amount) }}
            </div>
        @endif

        <!-- Remarks -->
        @if ($record->remarks)
            <div class="desc-card">
                <div class="desc-label">remarks</div>
                <div class="desc-text">{{ Str::limit($record->remarks, 90) }}</div>
            </div>
        @endif

        <!-- Summary Totals -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>
                <tr>
                    <td class="label">Receipt amount</td>
                    <td class="value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format($record->amount) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">New balance</td>
                    <td class="value {{ $updatedBalance >= 0 ? 'positive' : 'negative' }}">
                        {{ currency_format($updatedBalance) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear:both; height:0.06cm;"></div>

        <!-- Footer - Customer Copy with Receiving Stamp -->
        <div class="footer-note clearfix">
            <div class="footer-left"><span class="disclaimer-text">Computer generated</span></div>
            @if ($footerLogoPath)
                <div class="footer-center"><img style="max-height: 0.7cm;" src="{{ $footerLogoPath }}"
                        alt="Footer"></div>
            @endif
            <div class="footer-right"><span class="receipt-stamp">SIGNATURE</span></div>
        </div>
    </div>
</body>

</html>
