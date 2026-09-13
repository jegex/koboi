<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Jegex\Koboi\Contracts\Deletable as DeletableContract;
use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Contracts\Storable as StorableContract;
use Jegex\Koboi\Fields\Filters\Filter;
use Jegex\Koboi\Fields\Filters\TextFilter;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Support\Fluent;

class Trix extends Field implements DeletableContract, FilterableField, StorableContract
{
    use Expandable;
    use FieldFilterable;
    use HasAttachments;
    use SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'trix-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Model|Fluent  $model
     */
    protected function fillAttribute(NovaRequest $request, string $requestAttribute, object $model, string $attribute): ?callable
    {
        return $this->fillAttributeWithAttachment($request, $requestAttribute, $model, $attribute);
    }

    /**
     * Get the full path that the field is stored at on disk.
     *
     * @return string|null
     */
    public function getStoragePath()
    {
        return null;
    }

    /**
     * Make the field filter.
     *
     * @return Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new TextFilter($this);
    }

    /**
     * Prepare the field for JSON serialization.
     */
    public function serializeForFilter(): array
    {
        return transform($this->jsonSerialize(), static fn ($field) => Arr::only($field, [
            'uniqueKey',
            'name',
            'attribute',
        ]));
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'shouldShow' => $this->shouldBeExpanded(),
            'withFiles' => $this->withFiles,
        ]);
    }
}
