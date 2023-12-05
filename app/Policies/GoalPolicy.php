<?php

namespace App\Policies;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GoalPolicy
{
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
    public function view(User $user, Goal $goal): bool
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
    public function update(User $user, Goal $goal): bool
    {
        return $goal->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Goal $goal): bool
    {
        return $this->update($user, $goal);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Goal $goal): bool
    {
        return $this->update($user, $goal);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Goal $goal): bool
    {
        return $this->update($user, $goal);
    }
}
