<?php

namespace App\Support\Actions;

use Ariefng\FilamentCalculator\Actions\CalculatorAction as BaseCalculatorAction;

class CalculatorAction extends BaseCalculatorAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actionJs(fn(): string => <<<'JS'
        window.filamentCalculatorOriginInput = $el.closest('.fi-input-wrp')?.querySelector('input') ?? null
        JS
            . '; $wire.' . $this->getJsClickHandler());
    }
}
