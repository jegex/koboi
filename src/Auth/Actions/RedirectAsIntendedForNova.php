<?php

namespace Jegex\Koboi\Auth\Actions;

use Laravel\Fortify\Http\Responses\RedirectAsIntended;
use Jegex\Koboi\Nova;

class RedirectAsIntendedForNova extends RedirectAsIntended
{
    /** {@inheritDoc} */
    #[\Override]
    public function toResponse($request)
    {
        return redirect()->intended(Nova::initialPathUrl($request));
    }
}
