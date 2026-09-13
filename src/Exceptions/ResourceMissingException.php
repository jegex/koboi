<?php

namespace Jegex\Koboi\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class ResourceMissingException extends Exception
{
    /**
     * Construct a new exception.
     *
     * @param  Model  $model
     */
    public function __construct($model)
    {
        parent::__construct(
            __('Unable to find Resource for model [:model].', ['model' => $model::class])
        );
    }

    /**
     * Create a new exception instance.
     */
    public static function forRepeater(string $resource): static
    {
        return new static(
            __('Unable to find Resource for the given resource name [:resource]', ['resource' => $resource])
        );
    }
}
