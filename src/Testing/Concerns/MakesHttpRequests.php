<?php

namespace Jegex\Koboi\Testing\Concerns;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests as IlluminateMakesHttpRequests;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Symfony\Component\HttpFoundation\Request;

trait MakesHttpRequests
{
    use IlluminateMakesHttpRequests;

    /**
     * Create the request instance used for testing from the given Symfony request.
     *
     * @param  Request  $symfonyRequest
     * @return NovaRequest
     */
    protected function createTestRequest($symfonyRequest)
    {
        return NovaRequest::createFromBase($symfonyRequest);
    }
}
