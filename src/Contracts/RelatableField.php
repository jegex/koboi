<?php

namespace Jegex\Koboi\Contracts;

/**
 * @mixin \Jegex\Koboi\Fields\Field
 *
 * @property string $attribute
 * @property \Jegex\Koboi\Resource $resourceClass
 * @property string $resourceName
 */
interface RelatableField
{
    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName();

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType();
}
