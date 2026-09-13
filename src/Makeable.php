<?php

namespace Jegex\Koboi;

trait Makeable
{
    /**
     * Create a new instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }
}
