<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Net Position Breakdown</x-slot>

        <div class="grid grid-cols-3 gap-6">

            {{-- ASSETS --}}
            <div class="col-span-2 space-y-6">

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
                                <td class="py-2">Total Stock</td>
                                <td class="text-right py-2">
                                    Rs. {{ number_format($this->getData()['total_stock'], 0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Global figures --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                        Global Figures
                    </h3>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Receivable</td>
                                <td class="text-right py-2 text-info-600">
                                    Rs. {{ number_format($this->getData()['receivable'], 0) }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Accounts (Cash / Bank)</td>
                                <td class="text-right py-2 text-success-600">
                                    Rs. {{ number_format($this->getData()['accounts'], 0) }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Supplier Payable</td>
                                <td class="text-right py-2 text-danger-600">
                                    - Rs. {{ number_format($this->getData()['liabilities'], 0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- NET POSITION SUMMARY --}}
            <div class="flex flex-col justify-center space-y-4 border-l pl-6">

                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Total Assets</div>
                    <div class="text-xl font-semibold text-gray-800">
                        Rs. {{ number_format($this->getData()['total_assets'], 0) }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Receivable + Stock + Accounts
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Total Liabilities</div>
                    <div class="text-xl font-semibold text-danger-600">
                        - Rs. {{ number_format($this->getData()['total_liabilities'], 0) }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Supplier Payable
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <div class="text-xs text-gray-500 uppercase mb-1">Net Position</div>
                    <div class="text-3xl font-bold
                        {{ $this->getData()['net_position'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                        Rs. {{ number_format($this->getData()['net_position'], 0) }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Assets - Liabilities
                    </div>
                </div>

            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>