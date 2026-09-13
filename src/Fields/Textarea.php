<?php

namespace Jegex\Koboi\Fields;

use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Exceptions\NovaException;
use Jegex\Koboi\Fields\Filters\TextFilter;
use Jegex\Koboi\Http\Requests\NovaRequest;

class Textarea extends Field implements FilterableField
{
    use Expandable;
    use FieldFilterable;
    use SupportsDependentFields;
    use SupportsMaxlength;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'textarea-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * The number of rows used for the textarea.
     *
     * @var int
     */
    public $rows = 5;

    /**
     * Set the number of rows used for the textarea.
     *
     * @return $this
     */
    public function rows(int $rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Jegex\Koboi\Support\Fluent|object  $resource
     */
    #[\Override]
    public function resolveForDisplay($resource, ?string $attribute = null): void
    {
        parent::resolveForDisplay($resource, $attribute);

        $this->value = e($this->value);
    }

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
     * Specify that the element should be visible on the index view.
     *
     * @param  (callable():(bool))|bool  $callback
     * @return never
     *
     * @throws \Jegex\Koboi\Exceptions\HelperNotSupported
     */
    public function showOnIndex($callback = true)
    {
        throw NovaException::helperNotSupported(__METHOD__, __CLASS__);
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'rows' => $this->rows,
            'shouldShow' => $this->shouldBeExpanded(),
        ]);
    }
}
