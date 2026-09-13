<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\DashboardRequest;
use Jegex\Koboi\Http\Resources\DashboardViewResource;

class DashboardController extends Controller
{
    /**
     * Return the details for the Dashboard.
     */
    public function __invoke(DashboardRequest $request, string $dashboard = 'main'): JsonResponse
    {
        return DashboardViewResource::make($dashboard)->toResponse($request);
    }
}
