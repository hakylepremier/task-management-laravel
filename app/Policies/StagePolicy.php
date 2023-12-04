<?php

namespace App\Policies;

use App\Models\Stage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StagePolicy
{
    // TODO: change the permissions to allow only admin to create, update and delete.
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stage $stage): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stage $stage): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stage $stage): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Stage $stage): bool
    {
        return $user->is(auth()->user());
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Stage $stage): bool
    {
        return $user->is(auth()->user());
    }
}
