<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\DeleteLensResourceRequest;
use Jegex\Koboi\Jobs\DeleteResources;

class LensResourceDestroyController extends Controller
{
    /**
     * Destroy the given resource(s).
     */
    public function __invoke(DeleteLensResourceRequest $request): Response
    {
        DeleteResources::dispatchSync($request);

        return response()->noContent(200);
    }
}
