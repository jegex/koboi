<?php

namespace Jegex\Koboi\Fields;

class MorphMany extends HasMany
{
    /**
     * Get the relationship type.
     */
    #[\Override]
    public function relationshipType(): string
    {
        return 'morphMany';
    }
}
