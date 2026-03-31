<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Net Position Breakdown</x-slot>

        <div class="grid grid-cols-2 gap-6">

            {{-- Stock by Outlet --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                    Stock Value by Outlet
                </h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-1">Outlet</th>
                            <th class="text-right py-1">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->getData()['stock_by_outlet'] as $row)
                            <tr class="border-b border-gray-100">
                                <td class="py-1">{{ $row['outlet'] }}</td>
                                <td class="text-right py-1">
                                    Rs. {{ number_format($row['value'], 0) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold">
                            <td class="py-2">Total</td>
                            <td class="text-right py-2">
                                Rs. {{ number_format($this->getData()['total_stock'], 0) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Expenses by Outlet --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                    Expenses by Outlet
                </h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-1">Outlet</th>
                            <th class="text-right py-1">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->getData()['expenses_by_outlet'] as $row)
                            <tr class="border-b border-gray-100">
                                <td class="py-1">{{ $row['outlet'] }}</td>
                                <td class="text-right py-1">
                                    Rs. {{ number_format($row['amount'], 0) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold">
                            <td class="py-2">Total</td>
                            <td class="text-right py-2">
                                Rs. {{ number_format($this->getData()['total_expenses'], 0) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Net Position Summary --}}
        <div class="mt-6 pt-4 border-t">
            <div class="flex justify-between items-center">
                <div class="space-y-1 text-sm text-gray-600">
                    <div class="flex justify-between gap-16">
                        <span>Total Assets</span>
                        <span>Rs. {{ number_format($this->getData()['total_assets'], 0) }}</span>
                    </div>
                    <div class="flex justify-between gap-16">
                        <span>Total Liabilities</span>
                        <span class="text-danger-600">
                            - Rs. {{ number_format($this->getData()['total_liabilities'], 0) }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500 uppercase">Net Position</div>
                    <div class="text-2xl font-bold
                        {{ $this->getData()['net_position'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                        Rs. {{ number_format($this->getData()['net_position'], 0) }}
                    </div>
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>