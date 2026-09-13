<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPasswordViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return Inertia::render('Nova.ConfirmPassword')->toResponse($request);
    }
}
