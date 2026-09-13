<?php

namespace Jegex\Koboi\Menu;

use Jegex\Koboi\AuthorizedToSee;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Makeable;
use Jegex\Koboi\WithComponent;

/**
 * @method static static make(array $items)
 */
class MenuList implements \JsonSerializable
{
    use AuthorizedToSee;
    use Makeable;
    use WithComponent;

    /**
     * The menu's component.
     *
     * @var string
     */
    public $component = 'menu-list';

    /**
     * The menu's items.
     */
    public MenuCollection $items;

    /**
     * Construct a new Menu List instance.
     */
    public function __construct(iterable $items)
    {
        $this->items($items);
    }

    /**
     * Set menu's items.
     *
     * @return $this
     */
    public function items(iterable $items = [])
    {
        $this->items = new MenuCollection($items);

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

        return [
            'component' => $this->component,
            'items' => $this->items->authorized($request)->withoutEmptyItems()->all(),
        ];
    }
}
