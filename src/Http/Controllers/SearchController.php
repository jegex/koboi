<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Routing\Controller;
use Jegex\Koboi\GlobalSearch;
use Jegex\Koboi\Http\Requests\GlobalSearchRequest;
use Jegex\Koboi\Nova;

class SearchController extends Controller
{
    /**
     * Get the global search results for the given query.
     */
    public function __invoke(GlobalSearchRequest $request): array
    {
        return (new GlobalSearch(
            $request, Nova::globallySearchableResources($request)
        ))->get();
    }
}
