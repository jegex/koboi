<?php

namespace Jegex\Koboi\Http\Requests;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @property-read string|array<int, mixed> $resources
 */
class DeletionRequest extends NovaRequest
{
    use QueriesResources;

    /**
     * Get the selected models for the action in chunks.
     *
     * @param  Closure(Collection):(void)  $callback
     * @param  Closure(Collection):(Collection)  $authCallback
     */
    protected function chunkWithAuthorization(int $count, Closure $callback, Closure $authCallback): void
    {
        $model = $this->model();

        $this->toSelectedResourceQuery()->when(! $this->allResourcesSelected(), function ($query) {
            $query->whereKey($this->resources);
        })->tap(static function ($query) {
            $query->getQuery()->orders = [];
        })->chunkById($count, static function ($models) use ($callback, $authCallback) {
            $models = $authCallback($models);

            if ($models->isNotEmpty()) {
                $callback($models);
            }
        }, $model->getQualifiedKeyName(), $model->getKeyName());
    }

    /**
     * Get the query for the models that were selected by the user.
     */
    protected function toSelectedResourceQuery(): Builder
    {
        if ($this->allResourcesSelected()) {
            return $this->toQuery();
        }

        return $this->newQueryWithoutScopes();
    }
}
