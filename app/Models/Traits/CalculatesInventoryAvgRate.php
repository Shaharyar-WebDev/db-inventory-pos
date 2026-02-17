<?php

namespace App\Models\Traits;

use App\Models\Inventory\InventoryLedger;
use Carbon\Carbon;

trait CalculatesInventoryAvgRate
{
    public function getAvgRate(): float
    {
        return InventoryLedger::getAvgRateQueryForProduct($this->id)
            ->value('avg_rate') ?? 0;
    }

    public function getAvgRateOfSubUnit(): float
    {
        if (! $this->subUnit) {
            return 0; // no sub-unit, so no sub-unit rate
        }

        $avgRate = InventoryLedger::getAvgRateQueryForProduct($this->id)
            ->value('avg_rate') ?? 0;

        $conversion = (float) $this->sub_unit_conversion;

        if ($conversion <= 0) {
            throw new \RuntimeException("Invalid sub-unit conversion for product ID {$this->id}");
        }

        return $avgRate / $conversion;
    }

    public function getAvgRateAsOf($asOf = null): float
    {
        $asOf = $asOf ? Carbon::parse($asOf) : now();

        return InventoryLedger::getAvgRateQueryForProduct($this->id)
            ->when($asOf, fn($query) => $query->where('created_at', '<', $asOf))
            ->value('avg_rate') ?? 0;
    }

    public function getAvgRateOfSubUnitAsOf($asOf = null): float
    {
        if (! $this->subUnit) {
            return 0; // no sub-unit, so no sub-unit rate
        }

        $asOf = $asOf ? Carbon::parse($asOf) : now();

        $avgRate = InventoryLedger::getAvgRateQueryForProduct($this->id)
            ->when($asOf, fn($query) => $query->where('created_at', '<', $asOf))
            ->value('avg_rate') ?? 0;

        $conversion = (float) $this->sub_unit_conversion;

        if ($conversion <= 0) {
            throw new \RuntimeException("Invalid sub-unit conversion for product ID {$this->id}");
        }

        return $avgRate / $conversion;
    }

    public function getAvgRateOfUnitAsOf($asOf = null, ?int $unitId = null): float
    {
        $asOf = $asOf ? Carbon::parse($asOf) : now();

        if ($this->subUnit && $unitId === $this->subUnit->id) {
            // Return historical sub-unit rate
            return $this->getAvgRateOfSubUnitAsOf($asOf);
        }

        // Otherwise, return historical base-unit rate
        return $this->getAvgRateAsOf($asOf);
    }
}
