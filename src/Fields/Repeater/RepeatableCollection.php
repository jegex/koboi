<?php

namespace Jegex\Koboi\Fields\Repeater;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RepeatableCollection extends Collection
{
    /**
     * Find a Repeatable class by its key.
     *
     * @param  string  $key
     * @return Repeatable
     */
    public function findByKey($key)
    {
        return $this->first(function ($item) use ($key) {
            return $item->key() === $key;
        });
    }

    /**
     * Return a new instance of a Repeatable by its key.
     *
     * @param  string  $key
     * @param  Model|array  $data
     * @return Repeatable
     */
    public function newRepeatableByKey($key, $data = [])
    {
        $block = $this->findByKey($key);

        return new $block($data);
    }

    /**
     * Return the first Repeatable by its model class.
     *
     * @param  class-string  $class
     * @return callable|mixed|null
     */
    public function findByModelClass($class)
    {
        return $this->first(function ($item) use ($class) {
            return $item::$model === $class;
        });
    }

    /**
     * Return a new instance of a Repeatable by its model class.
     *
     * @param  Model  $model
     * @return Repeatable
     */
    public function newRepeatableByModel($model)
    {
        $repeatable = $this->findByModelClass($model::class);

        return new $repeatable($model);
    }
}
