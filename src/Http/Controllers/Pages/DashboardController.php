<?php

namespace Jegex\Koboi\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Jegex\Koboi\Http\Requests\DashboardRequest;
use Jegex\Koboi\Http\Resources\DashboardViewResource;
use Jegex\Koboi\Menu\Breadcrumb;
use Jegex\Koboi\Menu\Breadcrumbs;
use Jegex\Koboi\Nova;

class DashboardController extends Controller
{
    /**
     * Show Resource Create page using Inertia.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(DashboardRequest $request, string $name = 'main'): Response
    {
        DashboardViewResource::make($name)->authorizedDashboardForRequest($request);

        return Inertia::render('Nova.Dashboard', [
            'breadcrumbs' => $this->breadcrumbs($request, $name),
            'name' => $name,
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     */
    protected function breadcrumbs(DashboardRequest $request, string $name): Breadcrumbs
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Dashboards')),
            Breadcrumb::make(Nova::dashboardForKey($name, $request)->label()),
        ]);
    }
}
