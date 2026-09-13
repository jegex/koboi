<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Jegex\Koboi\Nova;
use Laravel\Fortify\Contracts\LoginViewResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class LoginViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return Inertia::render('Nova.Login', [
            'username' => Nova::fortify()->username,
            'email' => Nova::fortify()->email,
        ])->toResponse($request);
    }
}
