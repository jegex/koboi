<?php

namespace Jegex\Koboi\Http\Middleware;

use Jegex\Koboi\Events\NovaServiceProviderRegistered;
use Jegex\Koboi\Util;

class ServeNova
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
        if (Util::isNovaRequest($request)) {
            NovaServiceProviderRegistered::dispatch();
        }

        return $next($request);
    }
}
