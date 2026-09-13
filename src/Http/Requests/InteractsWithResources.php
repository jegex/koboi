<?php

namespace Jegex\Koboi\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jegex\Koboi\Contracts\QueryBuilder;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Resource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait InteractsWithResources
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the requested resource is soft deleting.
     *
     * @return bool
     */
    public function resourceSoftDeletes()
    {
        $resourceClass = $this->resource();

        return $resourceClass::softDeletes();
    }

    /**
     * Get the class name of the resource being requested.
     *
     * @return class-string<\Jegex\Koboi\Resource>
     *
     * @throws NotFoundHttpException
     */
    public function resource()
    {
        return tap(once(function () {
            return Nova::resourceForKey($this->route('resource'));
        }), static function ($resource) {
            abort_if(\is_null($resource), 404);
        });
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function newResource()
    {
        $resourceClass = $this->resource();

        return new $resourceClass($this->model());
    }

    /**
     * Find the resource instance for the request or abort.
     *
     * @param  string|int|null  $resourceId
     * @return \Jegex\Koboi\Resource<Model>
     *
     * @throws ModelNotFoundException
     */
    public function findResourceOrFail($resourceId = null)
    {
        return $this->newResourceWith($this->findModelOrFail($resourceId));
    }

    /**
     * Find the resource instance for the request.
     *
     * @param  string|int|null  $resourceId
     * @return \Jegex\Koboi\Resource
     */
    public function findResource($resourceId = null)
    {
        return $this->newResourceWith($this->findModel($resourceId));
    }

    /**
     * Find the model instance for the request or throw an exception.
     *
     * @param  string|int|null  $resourceId
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function findModelOrFail($resourceId = null)
    {
        if (! \is_null($resourceId)) {
            return $this->findModelQuery($resourceId)->firstOrFail();
        }

        return once(function () {
            return $this->findModelQuery()->firstOrFail();
        });
    }

    /**
     * Find the model instance for the request.
     *
     * @param  string|int|null  $resourceId
     * @return Model
     */
    public function findModel($resourceId = null)
    {
        return rescue(function () use ($resourceId) {
            return $this->findModelOrFail($resourceId);
        }, $this->model(), false);
    }

    /**
     * Get the query to find the model instance for the request.
     *
     * @param  mixed|null  $resourceId
     * @return \Illuminate\Contracts\Database\Eloquent\Builder
     */
    public function findModelQuery($resourceId = null)
    {
        return app()->make(QueryBuilder::class, [$this->resource()])
            ->whereKey(
                $this->newQueryWithoutScopes(),
                $resourceId ?? $this->resourceId
            )->toBase();
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @param  Model  $model
     * @return \Jegex\Koboi\Resource<Model>
     */
    public function newResourceWith($model)
    {
        $resourceClass = $this->resource();

        return new $resourceClass($model);
    }

    /**
     * Get a new query builder for the underlying model.
     *
     * @return Builder
     */
    public function newQuery()
    {
        return $this->model()->newQuery();
    }

    /**
     * Get a new, scopeless query builder for the underlying model.
     *
     * @return Builder
     */
    public function newQueryWithoutScopes()
    {
        return $this->model()->newQueryWithoutScopes();
    }

    /**
     * Get a new instance of the underlying model.
     *
     * @return Model
     */
    public function model()
    {
        $resourceClass = $this->resource();

        return $resourceClass::newModel();
    }
}
