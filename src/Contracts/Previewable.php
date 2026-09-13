<?php

namespace Jegex\Koboi\Contracts;

use Jegex\Koboi\Fields\Field;

/**
 * @mixin Field
 */
interface Previewable
{
    /**
     * Return a preview for the given field value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function previewFor($value);
}
