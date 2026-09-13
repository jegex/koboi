<?php

namespace Jegex\Koboi\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Jegex\Koboi\Support\Fluent;

/**
 * @property bool $pivot
 * @property string|null $pivotAccessor
 * @property MorphToMany|BelongsToMany|null $pivotRelation
 */
interface Resolvable
{
    /**
     * Resolve the element's value.
     *
     * @param  Model|Fluent|object|array  $resource
     */
    public function resolve($resource, ?string $attribute = null): void;

    /**
     * Resolve the field's value for display.
     *
     * @param  Model|Fluent|object|array  $resource
     */
    public function resolveForDisplay($resource, ?string $attribute = null): void;
}
