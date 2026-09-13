<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\RestoreLensResourceRequest;
use Jegex\Koboi\Jobs\RestoreResources;

class LensResourceRestoreController extends Controller
{
    /**
     * Force delete the given resource(s).
     */
    public function __invoke(RestoreLensResourceRequest $request): Response
    {
        RestoreResources::dispatchSync($request);

        return response()->noContent(200);
    }
}
