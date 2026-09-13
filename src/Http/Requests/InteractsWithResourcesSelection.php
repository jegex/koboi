<?php

namespace Jegex\Koboi\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property-read int|null $resourceId
 * @property-read array|string|null $resources
 */
trait InteractsWithResourcesSelection
{
    /**
     * Determine if currently all resources is selected.
     *
     * @return bool
     */
    public function allResourcesSelected()
    {
        return $this->resources === 'all';
    }

    /**
     * Get selected resource IDs.
     *
     * @return Collection<int, string|int>|null
     */
    public function selectedResourceIds()
    {
        if ($this->allResourcesSelected()) {
            return null;
        }

        $resourceIds = array_filter(! empty($this->resources) ? Arr::wrap($this->resources) : [$this->resourceId]);

        if (\count($resourceIds) < 1) {
            return collect();
        }

        return collect($resourceIds);
    }

    /**
     * Get selected resources.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Collection|null
     */
    public function selectedResources()
    {
        if ($this->allResourcesSelected()) {
            return null;
        }

        $resourceIds = array_filter(! empty($this->resources) ? Arr::wrap($this->resources) : [$this->resourceId]);

        if (\count($resourceIds) < 1) {
            return collect();
        }

        return $this->newQueryWithoutScopes()
            ->whereKey($resourceIds)
            ->get();
    }
}
