<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Jegex\Koboi\Nova;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $token = $request->route()->parameter('token');

        return Inertia::render('Nova.ResetPassword', [
            'token' => $token,
            'email' => $request->{Nova::fortify()->email},
        ])->toResponse($request);
    }
}
