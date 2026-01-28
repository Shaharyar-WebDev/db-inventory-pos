<div x-cloak x-show="$store.isLoading.value" class="global-loading-indicator">
    <div class="flex gap-2" style="
             display: flex;
    gap: 6px;">
        <div class="text-sm hidden sm:block">
            Processing
        </div>
        <x-filament::loading-indicator class="h-5 w-5" />
    </div>
    <script>
        document.addEventListener('alpine:init', () => Alpine.store('isLoading', {
            value: false,
            delayTimer: null,
            set(value) {
                clearTimeout(this.delayTimer);
                if (value === false) this.value = false;
                else this.delayTimer = setTimeout(() => this.value = true, 0);
            }
        }))
        document.addEventListener("livewire:init", () => {
            Livewire.hook('commit.prepare', () => Alpine.store('isLoading').set(true))
            Livewire.hook('commit', ({
                succeed
            }) => succeed(() => queueMicrotask(() => Alpine.store('isLoading').set(false))))
        })
    </script>
</div>

