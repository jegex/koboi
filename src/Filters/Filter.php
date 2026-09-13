<?php

namespace Jegex\Koboi\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Jegex\Koboi\AuthorizedToSee;
use Jegex\Koboi\Contracts\Filter as FilterContract;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Makeable;
use Jegex\Koboi\Metable;
use Jegex\Koboi\Nova;
use Jegex\Koboi\ProxiesCanSeeToGate;
use Jegex\Koboi\WithComponent;

abstract class Filter implements FilterContract, JsonSerializable
{
    use AuthorizedToSee;
    use Macroable;
    use Makeable;
    use Metable;
    use ProxiesCanSeeToGate;
    use Searchable;
    use WithComponent;

    /**
     * The displayable name of the filter.
     *
     * @var \Stringable|string
     */
    public $name;

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @return \Illuminate\Contracts\Database\Eloquent\Builder
     */
    abstract public function apply(NovaRequest $request, Builder $query, mixed $value);

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return \Stringable|string
     */
    public function name()
    {
        return $this->name ?: Nova::humanize($this);
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return $this::class;
    }

    /**
     * Set the default options for the filter.
     *
     * @return array|mixed
     */
    public function default()
    {
        return '';
    }

    /**
     * Prepare the filter for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return with(app(NovaRequest::class), function (NovaRequest $request) {
            $name = $this->name();
            $component = $this->component();

            return array_merge([
                'class' => $this->key(),
                'name' => $name,
                'uniqueKey' => \sprintf('%s-%s', Str::slug($name), $component),
                'component' => $component,
                'options' => collect($this->options($request))->map(static function ($value, $label) {
                    if (\is_array($value)) {
                        return array_merge(['label' => $label], $value);
                    } elseif (\is_string($label)) {
                        return ['label' => $label, 'value' => $value];
                    }

                    return ['label' => $value, 'value' => $value];
                })->values()->all(),
                'currentValue' => $this->default() ?? '',
                'searchable' => $this->isSearchable($request),
            ], $this->meta());
        });
    }
}
