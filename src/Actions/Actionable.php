<?php

namespace Jegex\Koboi\Actions;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jegex\Koboi\Nova;

trait Actionable
{
    /**
     * Get all of the action events for the user.
     *
     * @return MorphMany
     */
    public function actions()
    {
        return $this->morphMany(Nova::actionResource()::$model, 'actionable');
    }
}
