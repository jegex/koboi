<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\ResourceCreateOrAttachRequest;
use Jegex\Koboi\Http\Resources\CreateViewResource;
use Jegex\Koboi\Http\Resources\ReplicateViewResource;

class CreationFieldController extends Controller
{
    /**
     * List the creation fields for the given resource.
     *
     * @throws AuthorizationException
     */
    public function __invoke(ResourceCreateOrAttachRequest $request): JsonResponse
    {
        if ($request->has('fromResourceId')) {
            return ReplicateViewResource::make($request->fromResourceId)->toResponse($request);
        }

        return CreateViewResource::make()->toResponse($request);
    }
}
