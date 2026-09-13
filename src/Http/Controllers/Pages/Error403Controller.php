<?php

namespace Jegex\Koboi\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Error403Controller extends Controller
{
    /**
     * Show Nova 403 page using Inertia.
     *
     * @throws HttpException
     */
    public function __invoke(): never
    {
        abort(403);
    }
}
