<?php

namespace Jegex\Koboi\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jegex\Koboi\Nova;

class Authorize
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
        return Nova::check($request) ? $next($request) : abort(403);
    }
}
