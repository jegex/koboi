<?php

namespace Jegex\Koboi;

use Illuminate\Support\Collection;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Lenses\Lens;

trait ResolvesLenses
{
    /**
     * Get the lenses that are available for the given request.
     *
     * @return Collection<int, Lens>
     */
    public function availableLenses(NovaRequest $request): Collection
    {
        return $this->resolveLenses($request)->filter->authorizedToSee($request)->values();
    }

    /**
     * Get the lenses for the given request.
     *
     * @return Collection<int, Lens>
     */
    public function resolveLenses(NovaRequest $request): Collection
    {
        return collect(array_values($this->filter($this->lenses($request))));
    }

    /**
     * Get the lenses available on the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }
}
