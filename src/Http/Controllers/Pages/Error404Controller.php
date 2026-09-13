<?php

namespace Jegex\Koboi\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Error404Controller extends Controller
{
    /**
     * Show Nova 404 page using Inertia.
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(): never
    {
        abort(404);
    }
}
