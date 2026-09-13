<?php

namespace Jegex\Koboi\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Jegex\Koboi\Http\Requests\ResourceIndexRequest;
use Jegex\Koboi\Http\Resources\IndexViewResource;
use Jegex\Koboi\Menu\Breadcrumb;
use Jegex\Koboi\Menu\Breadcrumbs;
use Jegex\Koboi\Nova;

class ResourceIndexController extends Controller
{
    /**
     * Show Resource Index page using Inertia.
     */
    public function __invoke(ResourceIndexRequest $request): Response
    {
        $resourceClass = IndexViewResource::make()->authorizedResourceForRequest($request);

        return Inertia::render('Nova.Index', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'perPageOptions' => $resourceClass::perPageOptions(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     */
    protected function breadcrumbs(ResourceIndexRequest $request): Breadcrumbs
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
        ]);
    }
}
