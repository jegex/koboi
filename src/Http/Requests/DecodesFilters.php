<?php

namespace Jegex\Koboi\Http\Requests;

use Illuminate\Support\Collection;
use Jegex\Koboi\Filters\FilterDecoder;

/**
 * @property-read string $filters
 */
trait DecodesFilters
{
    /**
     * Get the filters for the request.
     */
    public function filters(): Collection
    {
        return (new FilterDecoder($this->filters, $this->availableFilters()))->filters();
    }

    /**
     * Get all of the possibly available filters for the request.
     */
    protected function availableFilters(): Collection
    {
        return $this->newResource()->availableFilters($this);
    }
}
