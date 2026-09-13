<?php

namespace Jegex\Koboi\Tests;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Jegex\Koboi\Events\NovaServiceProviderRegistered;
use Jegex\Koboi\Events\ServingNova;
use Jegex\Koboi\Http\Middleware\DispatchServingNovaEvent;
use Jegex\Koboi\Http\Middleware\HandleInertiaRequests;
use Jegex\Koboi\Http\Middleware\ServeNova;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;
use ReflectionClass;

it('serves nova routes under the nova-api prefix', function () {
    $routes = collect(app('router')->getRoutes()->getRoutes())
        ->map(fn ($route) => $route->uri())
        ->filter(fn ($uri) => str_starts_with($uri, 'nova-api'));

    expect($routes)->not->toBeEmpty()
        ->and($routes)->toContain('nova-api/scripts/{script}')
        ->and($routes)->toContain('nova-api/styles/{style}');
});

it('registers the nova middleware aliases and groups', function () {
    $aliases = (new ReflectionClass(Router::class))->getProperty('middleware');
    $aliases->setAccessible(true);

    expect(\array_keys($aliases->getValue(app('router'))))->toContain('nova.guest', 'nova.auth');

    $groups = app('router')->getMiddlewareGroups();
    expect($groups)->toHaveKeys(['nova', 'nova:api', 'nova:asset', 'nova:serving']);
});

it('uses the nova blade layout as the inertia root view', function () {
    $rootView = (new ReflectionClass(HandleInertiaRequests::class))->getProperty('rootView');
    $rootView->setAccessible(true);

    expect($rootView->getValue(new HandleInertiaRequests))->toBe('nova::layout')
        ->and(view()->exists('nova::layout'))->toBeTrue();
});

it('shares the expected nova props through inertia', function () {
    $share = (new HandleInertiaRequests)->share(Request::create('/nova'));

    expect($share)->toHaveKeys(['novaConfig', 'currentUser', 'validLicense'])
        ->and($share['novaConfig'])->toBeCallable();
});

it('serves the frontend configuration consumed by the client', function () {
    $request = Request::create('/nova', 'GET');
    $next = fn () => new Response('ok');

    (new DispatchServingNovaEvent)->handle($request, $next);

    $config = Nova::jsonVariables($request);

    expect($config)->toHaveKeys([
        'appName', 'timezone', 'translations', 'userTimezone', 'pagination',
        'locale', 'algoliaAppId', 'algoliaApiKey', 'version', 'debug', 'logo',
    ])->and($config['version'])->toBe('5.9.5 (Silver Surfer)');
});

it('dispatches the serving event for nova requests', function () {
    Event::fake([ServingNova::class]);

    (new DispatchServingNovaEvent)->handle(
        Request::create('/nova', 'GET'),
        fn () => new Response('ok'),
    );

    Event::assertDispatched(ServingNova::class);
});

it('dispatches the nova-service-provider-registered event for nova requests', function () {
    Event::fake([NovaServiceProviderRegistered::class]);

    $response = (new ServeNova)->handle(
        Request::create('/nova', 'GET'),
        fn () => new Response('ok'),
    );

    expect($response->getStatusCode())->toBe(200);
    Event::assertDispatched(NovaServiceProviderRegistered::class);
});

it('binds the NovaRequest to the app container', function () {
    $request = Request::create('/nova', 'GET');

    Container::getInstance()->instance(NovaRequest::class, $request);

    expect(Container::getInstance()->make(NovaRequest::class))->toBe($request);
});
