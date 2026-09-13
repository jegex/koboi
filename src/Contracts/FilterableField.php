<?php

namespace Jegex\Koboi\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Jegex\Koboi\Fields\Field;
use Jegex\Koboi\Fields\Filters\Filter;
use Jegex\Koboi\Http\Requests\NovaRequest;

/**
 * @mixin Field
 *
 * @method array jsonSerialize()
 *
 * @property string $attribute
 * @property callable|null $filterableCallback
 * @property string $name
 * @property string $resourceClass
 */
interface FilterableField
{
    /**
     * Apply the filter to the given query.
     *
     * @param  Builder  $query
     */
    public function applyFilter(NovaRequest $request, $query, mixed $value): void;

    /**
     * Make the field filter.
     *
     * @return Filter|null
     */
    public function resolveFilter(NovaRequest $request);

    /**
     * Prepare the field for JSON serialization.
     */
    public function serializeForFilter(): array;
}
