<?php

use App\Enums\Status;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

if (! function_exists('app_currency_symbol')) {
    function app_currency_symbol()
    {
        return 'Rs ';
    }
}

if (! function_exists('currency_format')) {
    function currency_format($value,  ?int $precision = 2)
    {
        return app_currency_symbol() . number_format($value, $precision);
    }
}

if (! function_exists('qty_format')) {
    function qty_format($value)
    {
        return number_format($value, 3);
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

        $query = $modelClass::withoutGlobalScopes();

        // Only call withTrashed if the model actually uses SoftDeletes
        if (in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
            $query->withTrashed();
        }

        $latestId = (int) $query->max('id');

        $nextId = $latestId + 1;

        // Format parts
        $datePart = now()->format(app_date_format());
        $sequencePart = str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Combine and return
        return strtoupper($prefix) . '-' . $datePart . '-' . $sequencePart;
    }
}

if (! function_exists('default_number_format')) {
    function default_number_format($value)
    {
        return number_format($value);
    }
}

function generate_loading_indicator_html(?ComponentAttributeBag $attributes = null, ?IconSize $size = null): Htmlable
{
    $size ??= IconSize::Medium;

    $attributes = ($attributes ?? new ComponentAttributeBag)->class([
        'fi-icon fi-loading-indicator',
        "fi-size-{$size->value}",
    ]);

    return new HtmlString(<<<HTML
            <svg {$attributes->toHtml()} xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-icon lucide-loader"><path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/></svg>
            HTML);
}

if (! function_exists('should_prevent_record_deletion_if_record_exists')) {
    function should_prevent_record_deletion_if_record_exists(Model $model): bool
    {
        $shouldPreventDeletion = config('software.prevent_record_deletion_if_ledger_exists')[$model::class] ?? false;
        return $shouldPreventDeletion;
    }
}

if (! function_exists('status_options')) {
    function status_options()
    {
        $statuses = collect(Status::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();

        return $statuses;
    }
}

if (! function_exists('status_options_colors')) {
    function status_options_colors()
    {
        $statuses = collect(Status::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->color()
        ])->toArray();

        return $statuses;
    }
}

if (! function_exists('default_ledger_casts')) {
    function default_ledger_casts()
    {
        return [
            'qty'   => 'decimal:3',
            'rate'  => 'decimal:2',
            'value' => 'decimal:2',
            'amount' => 'decimal:2'
        ];
    }
}

if (!function_exists('number_to_words_currency')) {
    function number_to_words_currency($number)
    {
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

        $integer = floor($number);
        $fraction = round(($number - $integer) * 100);

        $words = ucfirst($formatter->format($integer)) . ' rupees';

        return $words . ' only';
    }
}
