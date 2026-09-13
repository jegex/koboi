<?php

namespace Jegex\Koboi\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Jegex\Koboi\Http\Requests\LensRequest;
use Jegex\Koboi\Http\Resources\LensViewResource;
use Jegex\Koboi\Menu\Breadcrumb;
use Jegex\Koboi\Menu\Breadcrumbs;
use Jegex\Koboi\Nova;

class LensController extends Controller
{
    /**
     * Show Resource Lens page using Inertia.
     */
    public function __invoke(LensRequest $request): Response
    {
        $lens = LensViewResource::make()->authorizedLensForRequest($request);

        return Inertia::render('Nova.Lens', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $request->route('resource'),
            'lens' => $lens->uriKey(),
            'searchable' => $lens::searchable(),
            'perPageOptions' => $lens::perPageOptions() ?? $request->resource()::perPageOptions(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     */
    protected function breadcrumbs(LensRequest $request): Breadcrumbs
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
            Breadcrumb::make($request->lens()->name()),
        ]);
    }
}
