<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Support\Arr;
use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Fields\Filters\TextFilter;
use Jegex\Koboi\Http\Requests\NovaRequest;

class Text extends Field implements FilterableField
{
    use AsHTML;
    use Copyable;
    use FieldFilterable;
    use HasSuggestions;
    use SupportsAutoCompletion;
    use SupportsDependentFields;
    use SupportsMaxlength;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'text-field';

    /**
     * Make the field filter.
     *
     * @return \Jegex\Koboi\Fields\Filters\Filter
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
        return transform($this->jsonSerialize(), function ($field) {
            $field['suggestions'] = $field['suggestions'] ?? $this->resolveSuggestions(app(NovaRequest::class));

            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
                'suggestions',
                'type',
                'min',
                'max',
                'step',
                'pattern',
                'placeholder',
                'extraAttributes',
            ]);
        });
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        if ($request->isFormRequest()) {
            return array_merge(parent::jsonSerialize(), [
                'suggestions' => $this->resolveSuggestions($request),
            ]);
        }

        $displayedAs = $this->serializeDisplayedValueAsHtml($request);

        return array_merge(parent::jsonSerialize(), [
            'asHtml' => $this->asHtml,
            'displayedAs' => $displayedAs,
            'copyable' => $this->copyable,
        ]);
    }
}
