<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\LensRequest;
use Jegex\Koboi\Http\Resources\LensViewResource;

class LensController extends Controller
{
    /**
     * List the lenses for the given resource.
     */
    public function index(LensRequest $request): JsonResponse
    {
        return response()->json(
            $request->availableLenses()
        );
    }

    /**
     * Get the specified lens and its resources.
     */
    public function show(LensRequest $request): JsonResponse
    {
        return LensViewResource::make()->toResponse($request);
    }
}
