<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Style;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StyleController extends Controller
{
    /**
     * Serve the requested stylesheet.
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(NovaRequest $request): Style
    {
        $asset = collect(Nova::allStyles())
            ->filter(static fn ($asset) => $asset->name() === $request->style)
            ->first();

        abort_if(\is_null($asset), 404);

        return $asset;
    }
}
