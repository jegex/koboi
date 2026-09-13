<?php

namespace Jegex\Koboi\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Resource;

trait InteractsWithRelatedResources
{
    /**
     * Find the parent resource model instance for the request.
     *
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function findParentResource(string|int|null $resourceId = null): Resource
    {
        $resource = $this->viaResource();

        return new $resource($this->findParentModel($resourceId));
    }

    /**
     * Find the parent resource model instance for the request.
     *
     * @return \Jegex\Koboi\Resource<Model>
     *
     * @throws ModelNotFoundException
     */
    public function findParentResourceOrFail(string|int|null $resourceId = null): Resource
    {
        $resource = $this->viaResource();

        return new $resource($this->findParentModelOrFail($resourceId));
    }

    /**
     * Find the parent resource model instance for the request.
     */
    public function findParentModel(string|int|null $resourceId = null): ?Model
    {
        if (! $this->viaRelationship()) {
            return null;
        }

        return rescue(function () use ($resourceId) {
            return $this->findParentModelOrFail($resourceId);
        }, Nova::modelInstanceForKey($this->viaResource), false);
    }

    /**
     * Find the parent resource model instance for the request or abort.
     *
     *
     * @throws ModelNotFoundException
     */
    public function findParentModelOrFail(string|int|null $resourceId = null): Model
    {
        return once(function () use ($resourceId) {
            $query = Nova::modelInstanceForKey($this->viaResource)->newQueryWithoutScopes();

            if (! \is_null($resourceId)) {
                return $query->whereKey($resourceId)->firstOrFail();
            }

            return $query->findOrFail($this->viaResourceId);
        });
    }

    /**
     * Find the related resource instance for the request.
     *
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function findRelatedResource(string|int|null $resourceId = null): Resource
    {
        $resource = $this->relatedResource();

        return new $resource($this->findRelatedModel($resourceId));
    }

    /**
     * Find the related resource instance for the request or abort.
     *
     * @return \Jegex\Koboi\Resource<Model>
     *
     * @throws ModelNotFoundException
     */
    public function findRelatedResourceOrFail(string|int|null $resourceId = null): Resource
    {
        $resource = $this->relatedResource();

        return new $resource($this->findRelatedModelOrFail($resourceId));
    }

    /**
     * Find the related resource model instance for the request.
     */
    public function findRelatedModel(string|int|null $resourceId = null): Model
    {
        return rescue(function () use ($resourceId) {
            return $this->findRelatedModelOrFail($resourceId);
        }, Nova::modelInstanceForKey($this->relatedResource), false);
    }

    /**
     * Find the parent resource model instance for the request or abort.
     *
     *
     * @throws ModelNotFoundException
     */
    public function findRelatedModelOrFail(string|int|null $resourceId = null): Model
    {
        return once(function () use ($resourceId) {
            $query = Nova::modelInstanceForKey($this->relatedResource)->newQueryWithoutScopes();

            if (! \is_null($resourceId)) {
                return $query->whereKey($resourceId)->firstOrFail();
            }

            return $query->findOrFail($this->input($this->relatedResource));
        });
    }

    /**
     * Get the displayable pivot model name for a "via relationship" request.
     */
    public function pivotName(): string
    {
        if (! $this->viaRelationship()) {
            return Resource::DEFAULT_PIVOT_NAME;
        }

        $resource = Nova::resourceInstanceForKey($this->viaResource);

        if ($name = $resource->pivotNameForField($this, $this->viaRelationship)) {
            return $name;
        }

        $parentResource = $this->findParentResource();

        $parent = $parentResource->model();

        return ($parent && $parentResource->hasRelatableFieldOrRelationship($this, $this->viaRelationship))
            ? class_basename($parent->{$this->viaRelationship}()->getPivotClass())
            : Resource::DEFAULT_PIVOT_NAME;
    }

    /**
     * Get the class name of the "related" resource being requested.
     *
     * @return class-string<\Jegex\Koboi\Resource>|null
     */
    public function relatedResource(): ?string
    {
        return Nova::resourceForKey($this->relatedResource);
    }

    /**
     * Get a new instance of the "related" resource being requested.
     *
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function newRelatedResource(): Resource
    {
        $resourceClass = $this->relatedResource();

        return $resourceClass::newResource();
    }

    /**
     * Get the class name of the "via" resource being requested.
     *
     * @return class-string<\Jegex\Koboi\Resource>|null
     */
    public function viaResource(): ?string
    {
        return Nova::resourceForKey($this->viaResource);
    }

    /**
     * Get a new instance of the "via" resource being requested.
     *
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function newViaResource(): Resource
    {
        $resourceClass = $this->viaResource();

        return $resourceClass::newResource();
    }

    /**
     * Determine if the request is via a relationship.
     */
    public function viaRelationship(): bool
    {
        return filled($this->viaResource) && filled($this->viaResourceId) && $this->viaRelationship;
    }

    /**
     * Determine if this request is via a many-to-many relationship.
     */
    public function viaManyToMany(): bool
    {
        return \in_array(
            $this->relationshipType,
            ['belongsToMany', 'morphToMany']
        );
    }
}
