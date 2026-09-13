<?php

namespace Jegex\Koboi\Http\Controllers\Fortify;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController as Controller;

class PasswordResetLinkController extends Controller
{
    /** {@inheritDoc} */
    #[\Override]
    protected function broker(): PasswordBroker
    {
        return Password::broker(config('nova.passwords'));
    }
}
