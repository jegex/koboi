<?php

namespace Jegex\Koboi\Auth\Actions;

use Jegex\Koboi\Nova;
use Laravel\Fortify\Http\Responses\RedirectAsIntended;

class RedirectAsIntendedForNova extends RedirectAsIntended
{
    /** {@inheritDoc} */
    #[\Override]
    public function toResponse($request)
    {
        return redirect()->intended(Nova::initialPathUrl($request));
    }
}
