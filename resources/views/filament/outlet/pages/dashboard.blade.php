<x-filament-panels::page>
    {{-- Page content --}}
    @php
    dd(App\Models\Inventory\InventoryLedger::query()->toRawSql());
    @endphp
</x-filament-panels::page>
