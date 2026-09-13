<?php

namespace Jegex\Koboi\Tests;

use Jegex\Koboi\Nova;

it('registers the nova api and asset routes', function () {
    $routes = collect(app('router')->getRoutes()->getRoutes())
        ->map(fn ($route) => $route->uri())
        ->filter(fn ($uri) => str_starts_with($uri, 'nova-api'));

    $this->assertGreaterThan(10, $routes->count());
    $this->assertContains('nova-api/scripts/{script}', $routes->all());
    $this->assertContains('nova-api/styles/{style}', $routes->all());
});

it('boots the Nova runtime class under the Jegex\\Koboi namespace', function () {
    $this->assertSame('Jegex\Koboi\Nova', Nova::class);
    $this->assertSame('5.9.5 (Silver Surfer)', Nova::version());
});

it('exposes the nova config merged by the core provider', function () {
    $this->assertSame('simple', config('nova.pagination'));
    $this->assertNotNull(config('nova.middleware'));
});
