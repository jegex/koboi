<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NovaRequest;

class FilterController extends Controller
{
    /**
     * List the filters for the given resource.
     */
    public function __invoke(NovaRequest $request): JsonResponse
    {
        return response()->json($request->newResource()->availableFilters($request));
    }
}
