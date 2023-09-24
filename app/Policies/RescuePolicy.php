<?php

namespace App\Policies;

use App\Models\Rescue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RescuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([User::DONOR, User::VOLUNTEER, User::ADMIN]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rescue $rescue): bool
    {
        return $user->hasRole([User::DONOR, User::VOLUNTEER, User::ADMIN]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole([User::DONOR, User::ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rescue $rescue): bool
    {
        return $user->hasRole([User::DONOR, User::VOLUNTEER, User::ADMIN]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rescue $rescue): bool
    {
        return $user->hasRole([User::DONOR, User::VOLUNTEER, User::ADMIN]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rescue $rescue): bool
    {
        return $user->hasRole(User::ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rescue $rescue): bool
    {
        return $user->hasRole(User::ADMIN);
    }
}
