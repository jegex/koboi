<?php

namespace Jegex\Koboi\Testing\Browser\Components\Controls;

class RelationSelectControlComponent extends SelectControlComponent
{
    /** {@inheritDoc} */
    #[\Override]
    public function selector(): string
    {
        return "@{$this->attribute}-select";
    }
}
