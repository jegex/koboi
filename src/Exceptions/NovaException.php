<?php

namespace Jegex\Koboi\Exceptions;

use Exception;

class NovaException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  class-string  $class
     * @return \Jegex\Koboi\Exceptions\HelperNotSupported
     */
    public static function helperNotSupported(string $method, string $class)
    {
        return new HelperNotSupported("The {$method} helper method is not supported by the {$class} class.");
    }

    /**
     * Create a new exception instance.
     *
     * @return \Jegex\Koboi\Exceptions\ResourceMissingException
     */
    public static function missingResourceForRepeater(string $name)
    {
        return ResourceMissingException::forRepeater("Missing resource for repeater {$name}");
    }
}
