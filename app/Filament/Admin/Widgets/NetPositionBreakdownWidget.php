<?php

namespace App\Filament\Admin\Widgets;

use App\Services\NetPositionService;
use Filament\Widgets\Widget;

class NetPositionBreakdownWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.net-position-breakdown-widget';

    protected int | string | array $columnSpan = 'full';

    public function getData(): array
    {
        return NetPositionService::calculate();
    }
}
