<?php

namespace Jegex\Koboi\Testing\Browser\Components\Controls;

use Jegex\Koboi\Testing\Browser\Components\Component;

class SelectControlComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $attribute
    ) {
        //
    }

    /** {@inheritDoc} */
    public function selector(): string
    {
        return "select[dusk='{$this->attribute}']";
    }
}
