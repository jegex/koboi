<?php

namespace Jegex\Koboi\Http\Requests;

use Illuminate\Support\Collection;
use Jegex\Koboi\Metrics\Metric;
use Jegex\Koboi\Nova;

/**
 * @property-read string $dashboard
 * @property-read string $metric
 */
class DashboardMetricRequest extends NovaRequest
{
    /**
     * Get the metric instance for the given request.
     */
    public function metric(): Metric
    {
        return $this->availableMetrics()->first(function ($metric) {
            return $this->metric === $metric->uriKey();
        }) ?: abort(404);
    }

    /**
     * Get all of the possible metrics for the request.
     */
    public function availableMetrics(): Collection
    {
        return Collection::make(Nova::dashboardForKey($this->dashboard, $this)->cards())
            ->unique()
            ->filter->authorize($this)
            ->values()
            ->whereInstanceOf(Metric::class);
    }
}
