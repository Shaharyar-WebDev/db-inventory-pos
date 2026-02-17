<?php

namespace App\Support\Traits;

use App\Enums\PanelId;
use Carbon\Carbon;
use Filament\Facades\Filament;

trait DefaultPageFIlters
{
    protected function getStartDate(): ?Carbon
    {
        $date = $this->pageFilters['startDate'] ?? null;

        return $date ? Carbon::parse($date)->startOfDay() : null;
    }

    protected function getEndDate(): ?Carbon
    {
        $date = $this->pageFilters['endDate'] ?? null;

        return $date ? Carbon::parse($date)->endOfDay() : null;
    }

    protected function getOutletId(): ?int
    {
        $panel = Filament::getCurrentPanel();

        if ($panel && $panel->getId() === PanelId::OUTLET->id()) {
            $tenant = Filament::getTenant();

            return $tenant?->id;
        }

        return isset($this->pageFilters['outletId'])
            ? (int) $this->pageFilters['outletId']
            : null;
    }
}
