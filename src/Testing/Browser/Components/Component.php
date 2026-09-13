<?php

namespace Jegex\Koboi\Testing\Browser\Components;

use Jegex\Koboi\Testing\Browser\Concerns\InteractsWithElements;
use Laravel\Dusk\Component as BaseComponent;

abstract class Component extends BaseComponent
{
    use InteractsWithElements;
}
