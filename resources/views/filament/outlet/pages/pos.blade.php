<x-filament-panels::page>
    <style>
        .fi-main {
            padding-inline: 0 !important;
        }
    </style>
    <script>
        localStorage.setItem('isOpen', false);
    </script>
    <div class="pos-container">
        {{-- Left: POS UI --}}
        <form wire:submit="create" class="pos-form">
            {{ $this->form }}
        </form>

        {{-- Right: Table --}}
        <div class="pos-table">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
