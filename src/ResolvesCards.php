<?php

namespace Jegex\Koboi;

use Illuminate\Support\Collection;
use Jegex\Koboi\Http\Requests\NovaRequest;

trait ResolvesCards
{
    /**
     * Get the cards that are available for the given request.
     *
     * @return \Illuminate\Support\Collection<int, \Jegex\Koboi\Metrics\Metric|\Jegex\Koboi\Card>
     */
    public function availableCards(NovaRequest $request): Collection
    {
        return $this->resolveCards($request)
            ->filter(static fn ($card) => $card->onlyOnDetail === false && $card->authorize($request))
            ->values();
    }

    /**
     * Get the cards that are available for the given request.
     *
     * @return \Illuminate\Support\Collection<int, \Jegex\Koboi\Metrics\Metric|\Jegex\Koboi\Card>
     */
    public function availableCardsForDetail(NovaRequest $request): Collection
    {
        return $this->resolveCards($request)
            ->filter(static fn ($card) => $card->onlyOnDetail === true && $card->authorize($request))
            ->values();
    }

    /**
     * Get the cards for the given request.
     *
     * @return \Illuminate\Support\Collection<int, \Jegex\Koboi\Metrics\Metric|\Jegex\Koboi\Card>
     */
    public function resolveCards(NovaRequest $request): Collection
    {
        return collect(array_values($this->filter($this->cards($request))));
    }

    /**
     * Get the cards available on the entity.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }
}
