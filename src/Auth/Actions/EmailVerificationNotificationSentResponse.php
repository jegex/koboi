<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse as Responsable;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationNotificationSentResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse([
                'status' => Fortify::VERIFICATION_LINK_SENT,
            ], 200)
            : back()->with('status', Fortify::VERIFICATION_LINK_SENT);
    }
}
