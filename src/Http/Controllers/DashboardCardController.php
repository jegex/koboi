<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\DashboardRequest;

class DashboardCardController extends Controller
{
    /**
     * List the cards for the dashboard.
     */
    public function __invoke(DashboardRequest $request, string $dashboard = 'main'): JsonResponse
    {
        return response()->json(
            $request->availableCards($dashboard)
        );
    }
}
