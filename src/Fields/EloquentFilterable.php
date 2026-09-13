<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jegex\Koboi\Fields\Filters\EloquentFilter;
use Jegex\Koboi\Http\Requests\NovaRequest;

trait EloquentFilterable
{
    use Filterable;

    /**
     * Make the field filter.
     *
     * @return EloquentFilter|null
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new EloquentFilter($this);
    }

    /**
     * Define filterable attribute.
     *
     * @return string
     */
    abstract protected function filterableAttribute(NovaRequest $request);

    /**
     * Define the default filterable callback.
     *
     * @return callable(NovaRequest, Builder, mixed, string):void
     */
    abstract protected function defaultFilterableCallback();
}
