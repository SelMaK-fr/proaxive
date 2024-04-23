<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Counter;

class CounterService
{
    private $valueLabels;
    private $counters;

    public function __construct(array $valueLabels) {
        $this->valueLabels = $valueLabels;
        $this->initializeCounters();
    }

    private function initializeCounters(): void
    {
        $this->counters = array_fill_keys(array_values($this->valueLabels), 0);
    }

    public function updateCounter($value, $count): void
    {
        $valueLabel = $this->valueLabels[$value] ?? "Étape inconnue";
        $this->counters[$valueLabel] = $count;
    }

    public function getCounters() {
        return $this->counters;
    }
}