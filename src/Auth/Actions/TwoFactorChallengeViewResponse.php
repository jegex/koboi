<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorChallengeViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return Inertia::render('Nova.TwoFactorChallenge')->toResponse($request);
    }
}
