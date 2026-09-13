<?php

namespace Jegex\Koboi\Fields\Repeater\Presets;

use Illuminate\Support\Collection;
use Jegex\Koboi\Fields\Repeater\RepeatableCollection;
use Jegex\Koboi\Http\Requests\NovaRequest;

interface Preset
{
    /**
     * Save the field value to permanent storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Jegex\Koboi\Support\Fluent  $model
     */
    public function set(
        NovaRequest $request,
        string $requestAttribute,
        $model,
        string $attribute,
        RepeatableCollection $repeatables,
        string|int|null $uniqueField
    ): callable;

    /**
     * Retrieve the value from storage and hydrate the field's value.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Jegex\Koboi\Support\Fluent  $model
     */
    public function get(NovaRequest $request, $model, string $attribute, RepeatableCollection $repeatables): Collection;
}
