<?php

namespace App\Policies;

use App\Models\HayListing;
use App\Models\User;

class HayListingPolicy
{
    /**
     * Grant all abilities to admin users.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, HayListing $hayListing): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HayListing $hayListing): bool
    {
        return $user->id === $hayListing->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HayListing $hayListing): bool
    {
        return $user->id === $hayListing->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HayListing $hayListing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HayListing $hayListing): bool
    {
        return false;
    }
}
