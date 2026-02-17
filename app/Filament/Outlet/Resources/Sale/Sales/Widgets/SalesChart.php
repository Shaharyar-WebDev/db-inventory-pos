<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Widgets;

use App\Support\Traits\HasSalesWidgetFilters;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    use HasSalesWidgetFilters;

    protected ?string $heading = 'Sales Chart';

    protected static bool $isLazy = true;

    public ?string $pollingInterval = null;

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        return [
            'datasets' => [
                [
                    'label' => 'Sales Amount',
                    'data' => '',
                ],
            ],
            'labels' => '',
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
