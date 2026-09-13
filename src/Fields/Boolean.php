<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Jegex\Koboi\Contracts\FilterableField;
use Jegex\Koboi\Fields\Filters\BooleanFilter;
use Jegex\Koboi\Fields\Filters\Filter;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Resource;
use Jegex\Koboi\Support\Fluent;
use Jegex\Koboi\Support\UndefinedValue;

class Boolean extends Field implements FilterableField
{
    use FieldFilterable;
    use SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'boolean-field';

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'center';

    /**
     * The value to be used when the field is "true".
     *
     * @var mixed
     */
    public $trueValue = true;

    /**
     * The value to be used when the field is "false".
     *
     * @var mixed
     */
    public $falseValue = false;

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  \Jegex\Koboi\Resource|Model|object  $resource
     */
    #[\Override]
    protected function resolveAttribute($resource, string $attribute): ?bool
    {
        $value = parent::resolveAttribute($resource, $attribute);

        return ! \is_null($value)
            ? ($value == $this->trueValue ? true : false)
            : null;
    }

    /**
     * Resolve the default value for the field.
     *
     * @return UndefinedValue|bool|null
     */
    #[\Override]
    public function resolveDefaultValue(NovaRequest $request): mixed
    {
        if ($request->isCreateOrAttachRequest() || $request->isActionRequest()) {
            return parent::resolveDefaultValue($request) ?? false;
        }

        return null;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Model|Fluent  $model
     */
    protected function fillAttributeFromRequest(NovaRequest $request, string $requestAttribute, object $model, string $attribute): void
    {
        if (isset($request[$requestAttribute])) {
            $model->{$attribute} = $request[$requestAttribute] == 1
                ? $this->trueValue
                : $this->falseValue;
        }
    }

    /**
     * Specify the values to store for the field.
     *
     * @return $this
     */
    public function values(mixed $trueValue, mixed $falseValue)
    {
        return $this->trueValue($trueValue)->falseValue($falseValue);
    }

    /**
     * Specify the value to store when the field is "true".
     *
     * @return $this
     */
    public function trueValue(mixed $value)
    {
        $this->trueValue = $value;

        return $this;
    }

    /**
     * Specify the value to store when the field is "false".
     *
     * @return $this
     */
    public function falseValue(mixed $value)
    {
        $this->falseValue = $value;

        return $this;
    }

    /**
     * Make the field filter.
     *
     * @return Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new BooleanFilter($this);
    }

    /**
     * Prepare the field for JSON serialization.
     */
    public function serializeForFilter(): array
    {
        return transform(
            $this->jsonSerialize(),
            static fn ($field) => Arr::only($field, ['uniqueKey'])
        );
    }
}
