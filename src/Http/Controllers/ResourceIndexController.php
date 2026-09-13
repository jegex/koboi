<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\ResourceIndexRequest;
use Jegex\Koboi\Http\Resources\IndexViewResource;

class ResourceIndexController extends Controller
{
    /**
     * List the resources for administration.
     */
    public function __invoke(ResourceIndexRequest $request): JsonResponse
    {
        return IndexViewResource::make()->toResponse($request);
    }
}
