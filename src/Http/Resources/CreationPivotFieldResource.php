<?php

namespace Jegex\Koboi\Http\Resources;

use Illuminate\Auth\Access\AuthorizationException;
use Jegex\Koboi\Http\Requests\ResourceCreateOrAttachRequest;
use Jegex\Koboi\Resource as NovaResource;

class CreationPivotFieldResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  ResourceCreateOrAttachRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->newResourceWith($request)
            ->creationPivotFields(
                $request,
                $request->relatedResource
            )->applyDependsOnWithDefaultValues($request)->all();
    }

    /**
     * Get current resource for the request.
     *
     * @throws AuthorizationException
     */
    public function newResourceWith(ResourceCreateOrAttachRequest $request): NovaResource
    {
        return tap($request->newResourceWith($request->findModel()), static function ($resource) use ($request) {
            abort_unless($resource->hasRelatableFieldOrRelationship($request, $request->viaRelationship), 404);
        });
    }
}
