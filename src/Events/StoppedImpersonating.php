<?php

namespace Jegex\Koboi\Events;

use Illuminate\Contracts\Auth\Authenticatable;

class StoppedImpersonating
{
    /**
     * The impersonator user.
     *
     * @var Authenticatable
     */
    public $impersonator;

    /**
     * The impersonated user.
     *
     * @var Authenticatable
     */
    public $impersonated;

    /**
     * Create a new event instance.
     *
     * @param  Authenticatable  $impersonator
     * @param  Authenticatable  $impersonated
     */
    public function __construct(
        $impersonator,
        $impersonated,
        public ?string $redirectTo
    ) {
        $this->impersonator = $impersonator;
        $this->impersonated = $impersonated;
    }
}
