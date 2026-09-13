<?php

namespace Jegex\Koboi\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jegex\Koboi\Events\NovaServiceProviderRegistered;
use Jegex\Koboi\Util;

class ServeNova
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
        if (Util::isNovaRequest($request)) {
            NovaServiceProviderRegistered::dispatch();
        }

        return $next($request);
    }
}
