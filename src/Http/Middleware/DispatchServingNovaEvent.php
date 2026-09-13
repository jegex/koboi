<?php

namespace Jegex\Koboi\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Jegex\Koboi\Events\ServingNova;
use Jegex\Koboi\Http\Requests\NovaRequest;

class DispatchServingNovaEvent
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request):mixed  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $preventsAccessingMissingAttributes = Model::preventsAccessingMissingAttributes();

        if ($preventsAccessingMissingAttributes === true) {
            Model::preventAccessingMissingAttributes(false);
        }

        /** @var \Illuminate\Contracts\Foundation\Application $app */
        $app = Container::getInstance();

        ServingNova::dispatch($app, $request);

        $app->forgetInstance(NovaRequest::class);

        $response = $next($request);

        if ($preventsAccessingMissingAttributes === true) {
            Model::preventAccessingMissingAttributes(true);
        }

        return $response;
    }
}
