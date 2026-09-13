<?php

namespace Jegex\Koboi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jegex\Koboi\Koboi
 */
class Koboi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jegex\Koboi\Koboi::class;
    }
}
