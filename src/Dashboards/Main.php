<?php

namespace Jegex\Koboi\Dashboards;

use Illuminate\Support\Str;
use Jegex\Koboi\Cards\Help;
use Jegex\Koboi\Dashboard;

class Main extends Dashboard
{
    /** {@inheritDoc} */
    #[\Override]
    public function name()
    {
        return class_basename($this);
    }

    /** {@inheritDoc} */
    #[\Override]
    public function uriKey()
    {
        return Str::snake(class_basename($this));
    }

    /** {@inheritDoc} */
    public function cards()
    {
        return [
            new Help,
        ];
    }
}
