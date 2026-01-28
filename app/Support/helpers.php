<?php

use Illuminate\Support\Str;
use App\Models\Scopes\OutletScope;

if (! function_exists('app_currency_symbol')) {
    function app_currency_symbol()
    {
        return 'Rs ';
    }
}

if (! function_exists('app_date_format')) {
    function app_date_format()
    {
        return 'd-M-Y';
    }
}

if (! function_exists('app_date_time_format')) {
    function app_date_time_format()
    {
        return 'd-M-Y h:i a';
    }
}

if (! function_exists('generateDocumentNumber')) {
    function generateDocumentNumber(string $prefix, string $modelClass): string
    {
        // $latestId = (int) $modelClass::query()->latest('id')->value('id');
        $latestId = (int) $modelClass::withoutGlobalScopes()
            ->withTrashed()
            ->max('id');

        $nextId = $latestId + 1;

        // Format parts
        $datePart = now()->format(app_date_format());
        $sequencePart = str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Combine and return
        return strtoupper($prefix).'-'.$datePart.'-'.$sequencePart;
    }

}

if (! function_exists('default_number_format')) {
    function default_number_format($value)
    {
        return number_format($value);
    }
}



