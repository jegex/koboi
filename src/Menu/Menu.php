<?php

namespace Jegex\Koboi\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Makeable;
use JsonSerializable;

/**
 * @phpstan-type TMenu \Jegex\Koboi\Menu\MenuGroup|\Jegex\Koboi\Menu\MenuItem|\Jegex\Koboi\Menu\MenuList|\Jegex\Koboi\Menu\MenuSection
 *
 * @method static static make(array|iterable $items = [])
 */
class Menu implements JsonSerializable
{
    use Conditionable;
    use Makeable;

    /**
     * The items for the menu.
     */
    public Collection $items;

    /**
     * Create a new Menu instance.
     */
    public function __construct(iterable $items = [])
    {
        $this->items = new Collection($items);
    }

    /**
     * Wrap the given menu if not already wrapped.
     *
     * @return self|static
     */
    public static function wrap(self|iterable $menu)
    {
        return $menu instanceof self
            ? $menu
            : self::make($menu);
    }

    /**
     * Push items into the menu.
     *
     * @param  JsonSerializable|iterable  $items
     *
     * @phpstan-param TMenu|iterable $items
     *
     * @return $this
     */
    public function push(MenuGroup|MenuItem|MenuList|MenuSection|iterable $items = [])
    {
        return $this->append($items);
    }

    /**
     * Append items into the menu.
     *
     * @param  JsonSerializable|iterable  $items
     *
     * @phpstan-param TMenu|iterable $items
     *
     * @return $this
     */
    public function append(MenuGroup|MenuItem|MenuList|MenuSection|iterable $items = [])
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Prepend items to the menu.
     *
     * @param  JsonSerializable|iterable  $items
     *
     * @phpstan-param TMenu|iterable $items
     *
     * @return $this
     */
    public function prepend(MenuGroup|MenuItem|MenuList|MenuSection|iterable $items = [])
    {
        $this->items->prepend($items);

        return $this;
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        return $this->items->flatten()
            ->reject(static fn ($item) => method_exists($item, 'authorizedToSee') && ! $item->authorizedToSee($request))
            ->values()
            ->jsonSerialize();
    }
}
