<?php

namespace Jegex\Koboi\Jobs;

use Illuminate\Database\Eloquent\Model;
use Jegex\Koboi\DeleteField;
use Jegex\Koboi\Http\Requests\NovaRequest;

trait DeletesFields
{
    /**
     * Delete the deletable fields on the given model / resource.
     *
     * @param  Model  $model
     */
    protected function forceDeleteFields(NovaRequest $request, $model): void
    {
        $this->deleteFields($request, $model, false);
    }

    /**
     * Delete the deletable fields on the given model / resource.
     *
     * @param  Model  $model
     */
    protected function deleteFields(NovaRequest $request, $model, bool $skipSoftDeletes = true): void
    {
        if ($skipSoftDeletes && $request->newResourceWith($model)->softDeletes()) {
            return;
        }

        $request->newResourceWith($model)
            ->deletableFields($request)
            ->filter->isPrunable()
            ->each(static function ($field) use ($request, $model) {
                DeleteField::forRequest($request, $field, $model);
            });
    }
}
