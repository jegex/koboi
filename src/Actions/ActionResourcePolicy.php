<?php

namespace Jegex\Koboi\Actions;

use Illuminate\Foundation\Auth\User;

class ActionResourcePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     */
    public function viewAny($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     */
    public function view($user, ActionResource $actionResource): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     */
    public function create($user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     *
     * @param  User  $user
     */
    public function replicate($user, ActionResource $actionResource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     */
    public function update($user, ActionResource $actionResource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     */
    public function delete($user, ActionResource $actionResource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     */
    public function restore($user, ActionResource $actionResource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     */
    public function forceDelete($user, ActionResource $actionResource): bool
    {
        return false;
    }
}
