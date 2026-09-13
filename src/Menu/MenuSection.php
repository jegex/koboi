<?php

namespace Jegex\Koboi\Menu;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Jegex\Koboi\AuthorizedToSee;
use Jegex\Koboi\Dashboard;
use Jegex\Koboi\Exceptions\NovaException;
use Jegex\Koboi\Fields\Collapsable;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Lenses\Lens;
use Jegex\Koboi\Makeable;
use Jegex\Koboi\Resource;
use Jegex\Koboi\URL;
use Jegex\Koboi\WithBadge;
use Jegex\Koboi\WithComponent;
use Jegex\Koboi\WithIcon;
use JsonSerializable;
use Stringable;

/**
 * @method static static make(\Stringable|string $name, array|iterable $items = [], string $icon = 'collection')
 */
class MenuSection implements JsonSerializable
{
    use AuthorizedToSee;
    use Collapsable;
    use Conditionable;
    use Macroable;
    use Makeable;
    use WithBadge;
    use WithComponent;
    use WithIcon;

    /**
     * The menu's component.
     *
     * @var string
     */
    public $component = 'menu-section';

    /**
     * The element's icon.
     *
     * @var string|null
     */
    public $icon;

    /**
     * The menu's path.
     *
     * @var URL|string|null
     */
    public $path = null;

    /**
     * The menu's items.
     */
    public MenuCollection $items;

    /**
     * Construct a new Menu Section instance.
     *
     * @param  array|iterable  $items
     */
    public function __construct(
        public Stringable|string $name,
        iterable $items = [],
        ?string $icon = 'collection'
    ) {
        $this->items = new MenuCollection($items);
        $this->withIcon($icon);
    }

    /**
     * Create a menu from dashboard class.
     *
     * @param  class-string<Dashboard>  $dashboard
     * @return static
     */
    public static function dashboard(string $dashboard)
    {
        return with(new $dashboard, static function ($dashboard) {
            return static::make(
                $dashboard->label()
            )->path('/dashboards/'.$dashboard->uriKey())
                ->canSee(static fn ($request) => $dashboard->authorizedToSee($request));
        });
    }

    /**
     * Create a menu section from a resource class.
     *
     * @param  class-string<\Jegex\Koboi\Resource>  $resourceClass
     * @return static
     */
    public static function resource(string $resourceClass)
    {
        return static::make(
            $resourceClass::label()
        )->path('/resources/'.$resourceClass::uriKey())
            ->canSee(static fn ($request) => $resourceClass::availableForNavigation($request) && $resourceClass::authorizedToViewAny($request));
    }

    /**
     * Create a menu section from a lens class.
     *
     * @param  class-string<\Jegex\Koboi\Resource>  $resourceClass
     * @param  class-string<Lens>  $lensClass
     * @return static
     */
    public static function lens(string $resourceClass, string $lensClass)
    {
        return with(new $lensClass, static function ($lens) use ($resourceClass) {
            return static::make($lens->name())
                ->path('/resources/'.$resourceClass::uriKey().'/lens/'.$lens->uriKey())
                ->canSee(static fn ($request) => $lens->authorizedToSee($request));
        });
    }

    /**
     * Set path to the menu.
     *
     * @return $this
     *
     * @throws NovaException
     */
    public function path(URL|string|null $href)
    {
        $this->path = $href;

        if ($this->collapsable) {
            throw new NovaException('Link menu sections cannot also be collapsable.');
        }

        return $this;
    }

    /**
     * Set the menu section as collapsable.
     *
     * @return $this
     *
     * @throws NovaException
     */
    public function collapsable()
    {
        $this->collapsable = true;

        if ($this->path) {
            throw new NovaException('Link menu sections cannot also be collapsable.');
        }

        return $this;
    }

    /**
     * Set icon to the menu.
     *
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);
        $url = ! empty($this->path) ? URL::make($this->path) : null;

        return [
            'active' => optional($url)->active() ?? false,
            'badge' => $this->resolveBadge(),
            'collapsable' => $this->collapsable,
            'collapsedByDefault' => $this->collapsedByDefault,
            'component' => $this->component,
            'icon' => $this->icon,
            'items' => $this->items->authorized($request)->withoutEmptyItems()->all(),
            'key' => md5($this->name.'-'.$this->path),
            'name' => $this->name,
            'path' => (string) $url,
        ];
    }
}
