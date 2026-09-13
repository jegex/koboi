<?php

namespace Jegex\Koboi\Fields;

use Jegex\Koboi\Fields\Filters\EloquentFilter;
use Jegex\Koboi\Http\Requests\NovaRequest;

trait EloquentFilterable
{
    use Filterable;

    /**
     * Make the field filter.
     *
     * @return \Jegex\Koboi\Fields\Filters\EloquentFilter|null
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
     * @return callable(\Jegex\Koboi\Http\Requests\NovaRequest, \Illuminate\Contracts\Database\Eloquent\Builder, mixed, string):void
     */
    abstract protected function defaultFilterableCallback();
}
