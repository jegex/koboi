<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\DetachResourceRequest;
use Jegex\Koboi\Jobs\DetachResources;

class ResourceDetachController extends Controller
{
    /**
     * Detach the given resource(s).
     */
    public function __invoke(DetachResourceRequest $request): Response
    {
        DetachResources::dispatchSync($request);

        return response()->noContent(200);
    }
}
