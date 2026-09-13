<?php

namespace Jegex\Koboi\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Jegex\Koboi\Contracts\ImpersonatesUsers;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;

/**
 * @property string $email
 */
class UserResource extends JsonResource
{
    /** {@inheritDoc} */
    #[\Override]
    public function toArray($request)
    {
        if (app()->bound(NovaRequest::class)) {
            $resourceClass = Nova::resourceForModel($this->resource);

            if (! \is_null($resourceClass)) {
                $resource = $resourceClass::make($this->resource);
                $avatar = $resource->resolveAvatarField(app(NovaRequest::class));

                if (! \is_null($avatar)) {
                    $avatar = $avatar->resolveThumbnailUrl();
                }
            }
        }

        return array_merge(
            parent::toArray($request),
            [
                'avatar' => $avatar ?? null,
                'canImpersonate' => method_exists($this->resource, 'canImpersonate') && $this->resource->canImpersonate() === true,
                'impersonating' => app(ImpersonatesUsers::class)->impersonating($request),
            ],
        );
    }
}
