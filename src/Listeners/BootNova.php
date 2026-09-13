<?php

namespace Jegex\Koboi\Listeners;

use Jegex\Koboi\Nova;
use Jegex\Koboi\NovaServiceProvider;
use Jegex\Koboi\Tools\Dashboard;
use Jegex\Koboi\Tools\ResourceManager;

class BootNova
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (! app()->providerIsLoaded(NovaServiceProvider::class)) {
            app()->register(NovaServiceProvider::class);
        }

        $this->registerTools();
        $this->registerResources();
    }

    /**
     * Boot the standard Nova resources.
     */
    protected function registerResources(): void
    {
        Nova::resources([
            Nova::actionResource(),
        ]);

        Nova::bootResources();
    }

    /**
     * Boot the standard Nova tools.
     */
    protected function registerTools(): void
    {
        Nova::tools([
            new Dashboard,
            new ResourceManager,
        ]);
    }
}
