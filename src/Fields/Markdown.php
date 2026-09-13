<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Jegex\Koboi\Contracts\Deletable as DeletableContract;
use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Contracts\Previewable;
use Jegex\Koboi\Contracts\Storable as StorableContract;
use Jegex\Koboi\Fields\Filters\Filter;
use Jegex\Koboi\Fields\Filters\TextFilter;
use Jegex\Koboi\Fields\Markdown\CommonMarkPreset;
use Jegex\Koboi\Fields\Markdown\DefaultPreset;
use Jegex\Koboi\Fields\Markdown\MarkdownPreset;
use Jegex\Koboi\Fields\Markdown\ZeroPreset;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\ManagesPresets;
use Jegex\Koboi\Support\Fluent;

class Markdown extends Field implements DeletableContract, FilterableField, Previewable, StorableContract
{
    use Expandable;
    use FieldFilterable;
    use HasAttachments;
    use ManagesPresets;
    use Storable;
    use SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'markdown-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * The built-in presets for the Markdown field.
     *
     * @var array<string, class-string<MarkdownPreset>>
     */
    public $presets = [
        'default' => DefaultPreset::class,
        'commonmark' => CommonMarkPreset::class,
        'zero' => ZeroPreset::class,
    ];

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
     * Return a preview for the given field value.
     *
     * @param  string|null  $value
     * @return string
     */
    public function previewFor($value)
    {
        return $this->renderer()->convert($value ?? '');
    }

    /**
     * @return MarkdownPreset
     */
    public function renderer()
    {
        return new $this->presets[$this->preset];
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
            'preset' => $this->preset,
            'previewFor' => $this->previewFor($this->value ?? ''),
            'withFiles' => $this->withFiles,
        ]);
    }
}
