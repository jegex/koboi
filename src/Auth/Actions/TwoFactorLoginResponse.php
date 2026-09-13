<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jegex\Koboi\Nova;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorLoginResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $redirect = redirect()->intended(Nova::initialPathUrl($request));

        return $request->wantsJson()
            ? new JsonResponse([
                'redirect' => $redirect->getTargetUrl(),
            ], 200)
            : $redirect;
    }
}
