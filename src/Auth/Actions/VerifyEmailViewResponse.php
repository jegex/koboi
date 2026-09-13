<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return Inertia::render('Nova.EmailVerification', [
            'status' => $request->session()->get('status'),
        ])->toResponse($request);
    }
}
