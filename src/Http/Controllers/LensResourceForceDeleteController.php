<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\ForceDeleteLensResourceRequest;
use Jegex\Koboi\Jobs\ForceDeleteResources;

class LensResourceForceDeleteController extends Controller
{
    /**
     * Force delete the given resource(s).
     */
    public function __invoke(ForceDeleteLensResourceRequest $request): Response
    {
        ForceDeleteResources::dispatchSync($request);

        return response()->noContent(200);
    }
}
