<?php

namespace Jegex\Koboi\Http\Resources;

use Illuminate\Auth\Access\AuthorizationException;
use Jegex\Koboi\Contracts\ListableField;
use Jegex\Koboi\Contracts\RelatableField;
use Jegex\Koboi\Fields\BelongsTo;
use Jegex\Koboi\Fields\Field;
use Jegex\Koboi\Fields\FieldCollection;
use Jegex\Koboi\Fields\HasOne;
use Jegex\Koboi\Fields\MorphOne;
use Jegex\Koboi\Fields\MorphTo;
use Jegex\Koboi\Http\Requests\ResourceDetailRequest;
use Jegex\Koboi\Resource as NovaResource;

class DetailViewResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  ResourceDetailRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->newResourceWith($request);

        $this->authorizedResourceForRequest($request, $resource);

        $payload = with($resource->serializeForDetail($request, $resource), static function ($detail) use ($request) {
            $detail['fields'] = collect($detail['fields'])
                ->when($request->viaResource, static function ($fields) use ($request) {
                    return $fields->reject(static function ($field) use ($request) {
                        /** @var Field $field */
                        if ($field instanceof ListableField) {
                            return true;
                        } elseif (! $field instanceof RelatableField) {
                            return false;
                        }

                        $relatedResource = $field->resourceName == $request->viaResource;

                        return ($request->relationshipType === 'hasOne' && $field instanceof BelongsTo && $relatedResource) ||
                            ($request->relationshipType === 'morphOne' && $field instanceof MorphTo && $relatedResource) ||
                            (\in_array($request->relationshipType, ['hasOne', 'morphOne']) && ($field instanceof MorphOne || $field instanceof HasOne));
                    });
                })
                ->values()->all();

            return $detail;
        });

        /** @var FieldCollection<int, Field> $fields */
        $fields = new FieldCollection($payload['fields']);

        return [
            'title' => (string) $resource->title(),
            'panels' => $resource->availablePanelsForDetail($request, $resource, $fields),
            'resource' => $payload,
        ];
    }

    /**
     * Get current resource for the request.
     *
     * @throws AuthorizationException
     */
    public function newResourceWith(ResourceDetailRequest $request): NovaResource
    {
        return $request->newResourceWith(
            tap($request->findModelQuery(), static function ($query) use ($request) {
                $resourceClass = $request->resource();
                $resourceClass::detailQuery($request, $query);
            })->firstOrFail()
        );
    }

    /**
     * Determine if resource is authorized for the request.
     *
     * @throws AuthorizationException
     */
    public function authorizedResourceForRequest(ResourceDetailRequest $request, NovaResource $resource): void
    {
        $resource->authorizeToView($request);
    }
}
