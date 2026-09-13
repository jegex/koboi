<?php

namespace Jegex\Koboi\Testing\Concerns;

use Illuminate\Support\Facades\Http;
use Jegex\Koboi\Events\NovaServiceProviderRegistered;

trait InteractsWithNova
{
    /**
     * Setup interacts with Nova.
     */
    protected function setUpInteractsWithNova(): void
    {
        Http::fake([
            'nova.laravel.com/*' => Http::response([], 200),
        ]);

        NovaServiceProviderRegistered::dispatch();
    }
}
