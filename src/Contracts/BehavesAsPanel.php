<?php

namespace Jegex\Koboi\Contracts;

use Jegex\Koboi\Panel;

interface BehavesAsPanel
{
    /**
     * Make current field behaves as panel.
     *
     * @return Panel
     */
    public function asPanel();
}
