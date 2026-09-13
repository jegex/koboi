<?php

namespace Jegex\Koboi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Jegex\Koboi\Fields\Field;
use Jegex\Koboi\Http\Requests\NovaRequest;

trait FillsFields
{
    /**
     * Fill a new model instance using the given request.
     *
     * @param  Model  $model
     * @return array{Model, array<int, callable>}
     */
    public static function fill(NovaRequest $request, $model): array
    {
        return static::fillFields(
            $request, $model,
            (new static($model))
                ->creationFields($request)
                ->applyDependsOn($request)
                ->withoutComputed($request)
                ->withoutReadonly($request)
                ->withoutUnfillable()
        );
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  Model  $model
     * @return array{Model, array<int, callable>}
     */
    public static function fillForUpdate(NovaRequest $request, $model): array
    {
        return static::fillFields(
            $request, $model,
            (new static($model))
                ->updateFields($request)
                ->applyDependsOn($request)
                ->withoutComputed($request)
                ->withoutReadonly($request)
                ->withoutUnfillable()
        );
    }

    /**
     * Fill a new pivot model instance using the given request.
     *
     * @param  Model  $model
     * @param  Pivot  $pivot
     * @return array{Pivot, array<int, callable>}
     */
    public static function fillPivot(NovaRequest $request, $model, $pivot): array
    {
        $instance = new static($model);

        return static::fillFields(
            $request, $pivot,
            $instance
                ->creationPivotFields($request, $request->relatedResource)
                ->applyDependsOn($request)
                ->withoutReadonly($request)
                ->withoutUnfillable()
        );
    }

    /**
     * Fill a new pivot model instance using the given request.
     *
     * @param  Model  $model
     * @param  Pivot  $pivot
     * @return array{Pivot, array<int, callable>}
     */
    public static function fillPivotForUpdate(NovaRequest $request, $model, $pivot): array
    {
        $instance = new static($model);

        return static::fillFields(
            $request, $pivot,
            $instance->updatePivotFields($request, $request->relatedResource)
                ->applyDependsOn($request)
                ->withoutReadonly($request)
                ->withoutUnfillable()
        );
    }

    /**
     * Fill the given fields for the model.
     *
     * @template TModelOrPivot of \Illuminate\Database\Eloquent\Relations\Pivot|\Illuminate\Database\Eloquent\Model
     *
     * @param  TModelOrPivot  $model
     * @param  Collection<int, Field>  $fields
     * @return array{TModelOrPivot, array<int, callable>}
     */
    protected static function fillFields(NovaRequest $request, $model, Collection $fields): array
    {
        return [
            $model,
            $fields->map->fill($request, $model)
                ->filter(static fn ($callback) => \is_callable($callback))
                ->values()
                ->all(),
        ];
    }
}
