<?php

namespace Jegex\Koboi\Contracts;

interface BehavesAsPanel
{
    /**
     * Make current field behaves as panel.
     *
     * @return \Jegex\Koboi\Panel
     */
    public function asPanel();
}
