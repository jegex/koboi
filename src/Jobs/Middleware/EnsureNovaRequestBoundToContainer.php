<?php

namespace Jegex\Koboi\Jobs\Middleware;

use Closure;
use Jegex\Koboi\Http\Requests\NovaRequest;

class EnsureNovaRequestBoundToContainer
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     */
    public function handle(object $job, Closure $next): void
    {
        $boundedByMiddleware = false;

        /** @var \Jegex\Koboi\Http\Requests\NovaRequest|null $request */
        $request = optional($job)->request ?? null;

        if ($request instanceof NovaRequest) {
            if (! app()->bound(NovaRequest::class)) {
                app()->instance(NovaRequest::class, $request);
                $boundedByMiddleware = true;
            }
        }

        $next($job);

        if ($boundedByMiddleware) {
            app()->forgetInstance(NovaRequest::class);
        }
    }
}
