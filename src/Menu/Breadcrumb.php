<?php

namespace Jegex\Koboi\Menu;

use Illuminate\Support\Traits\Conditionable;
use Jegex\Koboi\AuthorizedToSee;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Makeable;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Resource;
use Jegex\Koboi\URL;
use JsonSerializable;
use Stringable;

use function Orchestra\Sidekick\Eloquent\model_exists;

class Breadcrumb implements JsonSerializable
{
    use AuthorizedToSee;
    use Conditionable;
    use Makeable;

    /**
     * Construct a new Breadcrumb instance.
     */
    public function __construct(
        public Stringable|string $name,
        public ?string $path = null
    ) {
        //
    }

    /**
     * Create a breadcrumb from a resource class.
     *
     * @param  \Jegex\Koboi\Resource|class-string<\Jegex\Koboi\Resource>  $resourceClass
     */
    public static function resource(Resource|string $resourceClass): static
    {
        if ($resourceClass instanceof Resource && model_exists($resourceClass->model())) {
            return static::make(
                Nova::__(':resource Details: :title', [
                    'resource' => $resourceClass::singularLabel(),
                    'title' => $resourceClass->title(),
                ])
            )->path('/resources/'.$resourceClass::uriKey().'/'.$resourceClass->getKey())
                ->canSee(static fn ($request) => $resourceClass->authorizedToView($request));
        }

        return static::make(
            Nova::__($resourceClass::label())
        )->path('/resources/'.$resourceClass::uriKey())
            ->canSee(static fn ($request) => $resourceClass::availableForNavigation($request) && $resourceClass::authorizedToViewAny($request));
    }

    /**
     * Set breadcrumb's path.
     *
     * @return $this
     */
    public function path(URL|string|null $href)
    {
        $this->path = $href;

        return $this;
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array{name: string, path: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'path' => $this->authorizedToSee(app(NovaRequest::class)) ? $this->path : null,
        ];
    }
}
