<?php

namespace Jegex\Koboi;

use Jegex\Koboi\Fields\FieldElement;

class ResourceToolElement extends FieldElement
{
    /**
     * Create a new resource tool.
     */
    public function __construct(?string $component = null)
    {
        parent::__construct($component);

        $this->onlyOnDetail();
    }
}
