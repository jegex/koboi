<?php

namespace Jegex\Koboi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Jegex\Koboi\NovaCoreServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            NovaCoreServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}