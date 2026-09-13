<?php

namespace Jegex\Koboi\Fields;

class MorphOne extends HasOne
{
    /**
     * Get the relationship type.
     */
    #[\Override]
    public function relationshipType(): string
    {
        return 'morphOne';
    }
}
