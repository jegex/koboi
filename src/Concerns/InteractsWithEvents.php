<?php

namespace Jegex\Koboi\Concerns;

use Closure;
use Illuminate\Support\Facades\Event;
use Jegex\Koboi\Events\NovaServiceProviderRegistered;
use Jegex\Koboi\Events\ServingNova;

trait InteractsWithEvents
{
    /**
     * Register an event listener for the Nova "booted" event.
     *
     * @param  (\Closure(\Jegex\Koboi\Events\NovaServiceProviderRegistered):(void))|string|array  $callback
     * @return void
     */
    public static function booted(Closure|string|array $callback)
    {
        Event::listen(NovaServiceProviderRegistered::class, $callback);
    }

    /**
     * Register an event listener for the Nova "serving" event.
     *
     * @param  (\Closure(\Jegex\Koboi\Events\ServingNova):(void))|string|array  $callback
     * @return void
     */
    public static function serving(Closure|string|array $callback)
    {
        Event::listen(ServingNova::class, $callback);
    }

    /**
     * Flush the persistent Nova state.
     *
     * @return void
     */
    public static function flushState()
    {
        static::fortify()->flush();

        static::$rtlCallback = null;
        static::$createUserCallback = null;
        static::$createUserCommandCallback = null;
        static::$dashboards = [];
        static::$jsonVariables = [];
        static::$resources = [];
        static::$resourcesByModel = [];
        static::$scripts = [];
        static::$styles = [];
        static::$tools = [];
    }
}
