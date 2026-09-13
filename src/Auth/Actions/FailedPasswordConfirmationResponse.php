<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedPasswordConfirmationResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

class FailedPasswordConfirmationResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $message = __('The provided password was incorrect.');

        throw ValidationException::withMessages([
            'password' => [$message],
        ]);
    }
}
