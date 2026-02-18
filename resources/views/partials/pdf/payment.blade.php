@php
    $generalSettings = app(App\Settings\GeneralSettings::class);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Voucher #{!! $record->payment_number !!}</title>
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

        .payment-wrapper {
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

        /* PAYMENT TYPE BADGE */
        .payment-type-card {
            width: 100%;
            margin: 0.3cm 0 0.5cm 0;
            border-collapse: collapse;
        }

        .payment-type-card td {
            vertical-align: middle;
            background: #f6f9fc;
            border: 1.5px solid #dde5ec;
            padding: 0.4cm;
        }

        .type-badge {
            background: {{ $record->amount < 0 ? '#c62828' : '#2e7d32' }};
            color: white;
            padding: 6px 19px;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            /* display: inline-block; */
        }

        .type-label {
            font-size: 12pt;
            color: #1c3b4f;
            font-weight: bold;
            margin-left: 15px;
        }

        /* PARTY CARDS - Supplier, Account, Purchase, Outlet */
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

        .badge-account {
            background: #4f6f84;
        }

        .badge-purchase {
            background: #6f8a9c;
        }

        /* amount card */
        .amount-card {
            width: 100%;
            margin: 0.5cm 0;
            border-collapse: collapse;
        }

        .amount-card td {
            vertical-align: middle;
            background: #f0f5fa;
            border: 1.5px solid #c5d5e2;
            padding: 0.5cm;
            text-align: center;
        }

        .amount-label {
            font-size: 12pt;
            color: #1f3f52;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-right: 20px;
        }

        .amount-value {
            font-size: 24pt;
            font-weight: bold;
            color: {{ $record->amount < 0 ? '#c62828' : '#2e7d32' }};
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

        .payment-stamp {
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

    <div class="payment-wrapper">

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
                <div class="doc-label">payment voucher</div>
                <div class="doc-number">{!! $record->payment_number !!}</div>
                <div class="doc-meta">
                    Date: {!! $record->created_at->format(app_date_time_format()) !!}<br>
                    @if ($record->created_at != $record->updated_at)
                        Last updated: {!! $record->updated_at->format(app_date_time_format()) !!}
                    @endif
                </div>
            </div>
        </div>

        <!-- PAYMENT TYPE -->
        <table class="payment-type-card" cellspacing="0">
            <tr>
                <td>
                    <span class="type-badge">{{ $record->amount < 0 ? 'REFUND / ADJUSTMENT' : 'PAYMENT' }}</span>
                    <span class="type-label">via {{ $record->paymentMethod->name ?? 'N/A' }}</span>
                </td>
            </tr>
        </table>

        <!-- Supplier and Account Details Side by Side -->
        <table class="party-section" cellspacing="0">
            <tr>
                <td class="left-cell">
                    <div class="party-title">supplier</div>
                    <div class="party-detail">
                        <strong>{!! $record->supplier->name !!}</strong><br>
                        {!! $record->supplier->address ?? 'Address not available' !!}<br>
                        @if ($record->supplier->contact)
                            tel: {!! $record->supplier->contact !!}<br>
                        @endif
                        @if ($record->supplier->email)
                            email: {!! $record->supplier->email !!}
                        @endif
                    </div>
                    <div class="badge">payee</div>
                </td>
                <td class="right-cell">
                    <div class="party-title">account</div>
                    <div class="party-detail">
                        <strong>{!! $record->account->name !!}</strong><br>
                        {!! $record->account->account_number ?? 'Account #: N/A' !!}<br>
                        @if ($record->account->description)
                            {{ Str::limit($record->account->description, 50) }}
                        @endif
                    </div>
                    <div class="badge badge-account">source</div>
                </td>
            </tr>
        </table>

        <!-- Purchase and Outlet Details (if purchase exists) -->
        <table class="party-section" cellspacing="0">
            <tr>
                <td class="left-cell">
                    <div class="party-title">purchase reference</div>
                    <div class="party-detail">
                        @if($record->purchase)
                            <strong>PO: {!! $record->purchase->purchase_number !!}</strong><br>
                            Date: {!! $record->purchase->created_at->format(app_date_time_format()) !!}<br>
                            Amount: {{ currency_format($record->purchase->grand_total) }}
                        @else
                            <em>Not linked to specific purchase</em>
                        @endif
                    </div>
                    @if($record->purchase)
                        <div class="badge badge-purchase">reference</div>
                    @endif
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
                    <div class="badge">location</div>
                </td>
            </tr>
        </table>

        <!-- AMOUNT HIGHLIGHT -->
        <table class="amount-card" cellspacing="0">
            <tr>
                <td>
                    <span class="amount-label">payment amount</span>
                    <span class="amount-value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format(abs($record->amount)) }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- REMARKS / NOTES -->
        @if ($record->remarks)
            <div class="desc-card">
                <div class="desc-label">payment remarks</div>
                <div class="desc-text">{!! nl2br(e($record->remarks)) !!}</div>
            </div>
        @endif

        <!-- SUMMARY TOTALS (Supplier Balance) -->
        <div class="clearfix">
            <table class="summary-panel" cellspacing="0">
                @php
                    $previousBalance = $record->supplier->getSupplierBalanceAsOf($record->created_at);
                    // Subtract because payment reduces balance (payment amount is negative in ledger)
                    $updatedBalance = $previousBalance - $record->amount;
                @endphp
                <tr>
                    <td class="label">Previous balance</td>
                    <td class="value">{{ currency_format($previousBalance) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment amount</td>
                    <td class="value {{ $record->amount < 0 ? 'negative' : 'positive' }}">
                        {{ currency_format($record->amount) }}
                    </td>
                </tr>
                <tr class="total-row">
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
                    Authorized by / signature
                </div>
                <div style="margin-top: 10px; font-size:9pt; color:#4f6f84;">
                    Generated: {{ now()->format(app_date_time_format()) }}
                </div>
            </div>
            <div class="meta-box">
                <div class="payment-stamp">PAYMENT VOUCHER</div>
                <div style="margin-top: 10px; font-size:9pt;">
                    <strong>Payment method:</strong> {{ $record->paymentMethod->name ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- LEDGER REFERENCE -->
        <hr>
        <div style="text-align:center; color:#869fac; font-size:7pt;">
            account ledger updated • supplier ledger updated • payment ref: {{ $record->payment_number }}
        </div>

    </div> <!-- end payment-wrapper -->

    <!-- BOTTOM SPACING -->
    <div style="margin-top:0.7cm; text-align:right; color:#92a9b9; font-size:8pt;">
        payment voucher • this is a system generated document
    </div>
</body>

</html>
