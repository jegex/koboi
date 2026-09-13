<?php

namespace Jegex\Koboi\Cards;

use Jegex\Koboi\Card;

class Help extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /** {@inheritDoc} */
    #[\Override]
    public function component()
    {
        return 'help-card';
    }
}
