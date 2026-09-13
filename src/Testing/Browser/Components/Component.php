<?php

namespace Jegex\Koboi\Testing\Browser\Components;

use Laravel\Dusk\Component as BaseComponent;
use Jegex\Koboi\Testing\Browser\Concerns\InteractsWithElements;

abstract class Component extends BaseComponent
{
    use InteractsWithElements;
}
