<?php

namespace Jegex\Koboi\Http\Resources;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jegex\Koboi\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Jegex\Koboi\Resource as NovaResource;

class UpdatePivotFieldResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  ResourceUpdateOrUpdateAttachedRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->newResourceWith($request);

        return [
            'title' => $resource->title(),
            'fields' => $resource->updatePivotFields(
                $request,
                $request->relatedResource
            )->applyDependsOnWithDefaultValues($request)->all(),
        ];
    }

    /**
     * Get current resource for the request.
     *
     * @return \Jegex\Koboi\Resource
     *
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function newResourceWith(ResourceUpdateOrUpdateAttachedRequest $request)
    {
        $resource = $this->authorizedResourceForRequest($request);

        $model = $resource->model();

        $relation = $model->{$request->viaRelationship}();

        $accessor = $relation->getPivotAccessor();

        if ($request->viaPivotId) {
            tap($relation->getPivotClass(), static function ($pivotClass) use ($relation, $request) {
                $relation->wherePivot((new $pivotClass)->getKeyName(), $request->viaPivotId);
            });
        }

        $model->setRelation(
            $accessor,
            $relation->withoutGlobalScopes()->findOrFail($request->relatedResourceId)->{$accessor}
        );

        return $resource;
    }

    /**
     * Determine if resource is authorized for the request.
     *
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function authorizedResourceForRequest(ResourceUpdateOrUpdateAttachedRequest $request): NovaResource
    {
        return tap($request->findResourceOrFail(), static function ($resource) use ($request) {
            abort_unless($resource->hasRelatableFieldOrRelationship($request, $request->viaRelationship), 404);
        });
    }
}
