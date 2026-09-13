<?php

namespace Jegex\Koboi\Menu;

use Jegex\Koboi\AuthorizedToSee;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Makeable;
use JsonSerializable;

class Breadcrumbs implements JsonSerializable
{
    use AuthorizedToSee;
    use Makeable;

    /**
     * Construct a new Breadcrumb instance.
     */
    public function __construct(
        public ?iterable $items = null
    ) {
        //
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array{name: string, path: string|null}|array
     */
    public function jsonSerialize(): array
    {
        return $this->authorizedToSee(app(NovaRequest::class))
            ? $this->items
            : [];
    }
}
