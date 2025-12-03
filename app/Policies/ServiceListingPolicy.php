<?php

namespace App\Policies;

use App\Models\ServiceListing;
use App\Models\User;

class ServiceListingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view the listings index/dashboard
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ServiceListing $serviceListing): bool
    {
        // Anyone (including guests) can view individual listings for public display
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create listings
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServiceListing $serviceListing): bool
    {
        // Only the owner can update their listing
        return $user->id === $serviceListing->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServiceListing $serviceListing): bool
    {
        // Only the owner can delete their listing
        return $user->id === $serviceListing->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ServiceListing $serviceListing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ServiceListing $serviceListing): bool
    {
        return false;
    }
}







