<?php

namespace Jegex\Koboi\Fields\Filters;

use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Fields\Field;

/**
 * @method static static make(\Jegex\Koboi\Contracts\FilterableField&\Jegex\Koboi\Fields\Field $field, string $resourceName)
 */
class BelongsToFilter extends EloquentFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'belongs-to-field';

    /**
     * Construct a new filter.
     *
     * @param  FilterableField&Field  $field
     * @param  class-string<\Jegex\Koboi\Resource>  $resourceName
     */
    public function __construct(
        FilterableField $field,
        public string $resourceName
    ) {
        parent::__construct($field);
    }

    /**
     * Prepare the filter for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'resourceName' => $this->resourceName,
        ]);
    }
}
