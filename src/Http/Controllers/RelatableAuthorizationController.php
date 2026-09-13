<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NovaRequest;

class RelatableAuthorizationController extends Controller
{
    /**
     * Get the relatable authorization status for the resource.
     */
    public function __invoke(NovaRequest $request): array
    {
        $parentResource = $request->findParentResourceOrFail();
        $resourceClass = $request->resource();

        if ($request->viaManyToMany()) {
            return ['authorized' => $parentResource->authorizedToAttachAny(
                $request, $request->model()
            )];
        }

        return ['authorized' => $parentResource->authorizedToAdd(
            $request, $request->model()
        ) && $resourceClass::authorizedToCreate($request)];
    }
}
