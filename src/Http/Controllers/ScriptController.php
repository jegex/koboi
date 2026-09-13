<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Script;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScriptController extends Controller
{
    /**
     * Serve the requested script.
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(NovaRequest $request): Script
    {
        $asset = collect(Nova::allScripts())
            ->filter(static fn ($asset) => $asset->name() === $request->script)
            ->first();

        abort_if(\is_null($asset), 404);

        return $asset;
    }
}
