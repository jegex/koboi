<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\ResourceDetailRequest;
use Jegex\Koboi\Http\Resources\DetailViewResource;

class ResourceShowController extends Controller
{
    /**
     * Display the resource for administration.
     */
    public function __invoke(ResourceDetailRequest $request): JsonResponse
    {
        return DetailViewResource::make()->toResponse($request);
    }
}
