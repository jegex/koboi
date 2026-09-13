<?php

namespace Jegex\Koboi\Concerns;

use Jegex\Koboi\PendingFortifyConfiguration;

trait InteractsWithFortify
{
    /**
     * The fortify resolvers.
     */
    public static ?PendingFortifyConfiguration $fortifyResolver = null;

    /**
     * Register the Fortify resolver.
     */
    public static function fortify(): PendingFortifyConfiguration
    {
        return static::$fortifyResolver ??= new PendingFortifyConfiguration;
    }
}
