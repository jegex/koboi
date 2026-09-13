<?php

namespace Jegex\Koboi\Auth\Actions;

use Jegex\Koboi\Nova;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as Action;
use Laravel\Fortify\Fortify;

class RedirectIfTwoFactorAuthenticatable extends Action
{
    /** {@inheritDoc} */
    #[\Override]
    protected function validateCredentials($request)
    {
        $authenticateUsingCallback = Fortify::$authenticateUsingCallback;

        if (! Nova::fortify()->usingIdenticalGuardOrModel()) {
            Fortify::$authenticateUsingCallback = null;
        }

        return tap(parent::validateCredentials($request), static function () use ($authenticateUsingCallback) {
            Fortify::$authenticateUsingCallback = $authenticateUsingCallback;
        });
    }
}
