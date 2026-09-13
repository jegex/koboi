<?php

namespace Jegex\Koboi\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jegex\Koboi\Events\ServingNova;
use Jegex\Koboi\Http\Requests\NovaRequest;

class DispatchServingNovaEvent
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request):mixed  $next
     * @return Response
     */
    public function handle($request, $next)
    {
        $preventsAccessingMissingAttributes = Model::preventsAccessingMissingAttributes();

        if ($preventsAccessingMissingAttributes === true) {
            Model::preventAccessingMissingAttributes(false);
        }

        /** @var Application $app */
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
