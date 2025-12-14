<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use PostHog\PostHog;

class AnalyticsService
{
    public function identify(User $user): void
    {
        if (! config('services.posthog.api_key')) {
            return;
        }

        PostHog::identify([
            'distinctId' => (string) $user->id,
            'properties' => [
                'email' => $user->email,
                'name' => $user->name,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function track(string $event, ?User $user = null, array $properties = []): void
    {
        if (! config('services.posthog.api_key')) {
            return;
        }

        $distinctId = $user ? (string) $user->id : $this->getAnonymousId();

        PostHog::capture([
            'distinctId' => $distinctId,
            'event' => $event,
            'properties' => $properties,
        ]);
    }

    public function trackUserRegistered(User $user): void
    {
        $this->identify($user);
        $this->track('user_registered', $user, [
            'email' => $user->email,
        ]);
    }

    public function trackUserLoggedIn(User $user): void
    {
        $this->identify($user);
        $this->track('user_logged_in', $user);
    }

    public function trackUserLoggedOut(User $user): void
    {
        $this->track('user_logged_out', $user);
    }

    public function trackEmailVerified(User $user): void
    {
        $this->track('email_verified', $user);
    }

    public function trackListingCreated(User $user, Model $listing, string $listingType): void
    {
        $this->track('listing_created', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackListingUpdated(User $user, Model $listing, string $listingType): void
    {
        $this->track('listing_updated', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackListingDeleted(User $user, Model $listing, string $listingType): void
    {
        $this->track('listing_deleted', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackListingViewed(Model $listing, string $listingType, ?User $viewer = null): void
    {
        $this->track('listing_viewed', $viewer, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackSearchPerformed(?User $user, array $filters, int $resultCount): void
    {
        $this->track('search_performed', $user, [
            'query' => $filters['search'] ?? null,
            'categories' => $filters['categories'] ?? [],
            'breed' => $filters['breed'] ?? null,
            'location' => $filters['location'] ?? null,
            'price_min' => $filters['price_min'] ?? null,
            'price_max' => $filters['price_max'] ?? null,
            'sort' => $filters['sort'] ?? 'newest',
            'result_count' => $resultCount,
        ]);
    }

    public function trackCheckoutInitiated(User $user, Model $listing, string $listingType): void
    {
        $this->track('checkout_initiated', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackSubscriptionCreated(User $user, Model $listing, string $listingType): void
    {
        $this->track('subscription_created', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    public function trackSubscriptionCancelled(User $user, Model $listing, string $listingType): void
    {
        $this->track('subscription_cancelled', $user, [
            'listing_type' => $listingType,
            'listing_id' => $listing->id,
        ]);
    }

    private function getAnonymousId(): string
    {
        return session()->getId() ?: 'anonymous_' . uniqid();
    }
}
