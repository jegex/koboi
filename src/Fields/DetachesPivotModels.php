<?php

namespace Jegex\Koboi\Fields;

use Jegex\Koboi\Contracts\Deletable;
use Jegex\Koboi\DeleteField;
use Jegex\Koboi\Nova;

trait DetachesPivotModels
{
    /**
     * Get the pivot record detachment callback for the field.
     *
     * @return callable(\Jegex\Koboi\Http\Requests\NovaRequest, mixed):bool
     */
    protected function detachmentCallback(): callable
    {
        return function ($request, $model) {
            $pivotAccessor = $model->{$this->attribute}()->getPivotAccessor();

            foreach ($model->{$this->attribute}()->withoutGlobalScopes()->lazy() as $related) {
                $resource = Nova::newResourceFromModel($related);

                $pivot = $related->{$pivotAccessor};

                $pivotFields = $resource->resolvePivotFields($request, $request->resource);

                $pivotFields->whereInstanceOf(Deletable::class)
                        ->filter->isPrunable()
                        ->each(static function ($field) use ($request, $pivot) {
                            /** @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Deletable $field */
                            DeleteField::forRequest($request, $field, $pivot)->save();
                        });

                $pivot->delete();
            }

            return true;
        };
    }
}
