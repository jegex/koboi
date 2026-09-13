<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\RestoreResourceRequest;
use Jegex\Koboi\Jobs\RestoreResources;

class ResourceRestoreController extends Controller
{
    /**
     * Restore the given resource(s).
     */
    public function __invoke(RestoreResourceRequest $request): Response
    {
        RestoreResources::dispatchSync($request, $request->resource());

        return response()->noContent(200);
    }
}
