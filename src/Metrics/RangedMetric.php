<?php

namespace Jegex\Koboi\Metrics;

use Illuminate\Support\Collection;

abstract class RangedMetric extends Metric
{
    /**
     * The ranges available for the metric.
     *
     * @var Collection<string|int, string>|array<string|int, string>
     */
    public $ranges = [];

    /**
     * The selected range key.
     *
     * @var string|null
     */
    public $selectedRangeKey = null;

    /**
     * Get the ranges available for the metric.
     *
     * @return Collection|array
     */
    public function ranges()
    {
        return $this->ranges;
    }

    /**
     * Set the default range.
     *
     * @return $this
     */
    public function defaultRange(string $key)
    {
        $this->selectedRangeKey = $key;

        return $this;
    }

    /**
     * Prepare the metric for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'selectedRangeKey' => $this->selectedRangeKey,
            'ranges' => collect($this->ranges())
                ->map(static fn ($range, $key) => ['label' => $range, 'value' => $key])
                ->values()
                ->all(),
        ]);
    }
}
