<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\DeleteResourceRequest;
use Jegex\Koboi\Jobs\DeleteResources;
use Jegex\Koboi\URL;

class ResourceDestroyController extends Controller
{
    /**
     * Destroy the given resource(s).
     */
    public function __invoke(DeleteResourceRequest $request): JsonResponse|Response
    {
        DeleteResources::dispatchSync($request, $request->resource());

        if ($request->isForSingleResource() && ! \is_null($redirect = $request->resource()::redirectAfterDelete($request))) {
            return response()->json([
                'redirect' => URL::make($redirect),
            ]);
        }

        return response()->noContent(200);
    }
}
