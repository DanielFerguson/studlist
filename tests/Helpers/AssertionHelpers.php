<?php

namespace Tests\Helpers;

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Laravel\Cashier\Subscription;
use PHPUnit\Framework\Assert;

trait AssertionHelpers
{
    /**
     * Assert that a listing is active
     */
    protected function assertListingIsActive(SteerListing|StudListing $listing): void
    {
        $listing->refresh();
        Assert::assertEquals('active', $listing->status, "Expected listing {$listing->id} to be active but was {$listing->status}");
        Assert::assertNotNull($listing->stripe_subscription_id, "Expected listing {$listing->id} to have a stripe_subscription_id");
    }

    /**
     * Assert that a listing is cancelled
     */
    protected function assertListingIsCancelled(SteerListing|StudListing $listing): void
    {
        $listing->refresh();
        Assert::assertEquals('cancelled', $listing->status, "Expected listing {$listing->id} to be cancelled but was {$listing->status}");
    }

    /**
     * Assert that a listing is draft
     */
    protected function assertListingIsDraft(SteerListing|StudListing $listing): void
    {
        $listing->refresh();
        Assert::assertEquals('draft', $listing->status, "Expected listing {$listing->id} to be draft but was {$listing->status}");
    }

    /**
     * Assert that a subscription exists for the user and listing
     */
    protected function assertSubscriptionExists(User $user, SteerListing|StudListing $listing): void
    {
        $type = $listing instanceof SteerListing ? 'steer' : 'stud';
        $subscriptionType = "{$type}_{$listing->id}";
        
        $subscription = $user->subscriptions()
            ->where('type', $subscriptionType)
            ->first();
            
        Assert::assertNotNull($subscription, "Expected subscription of type {$subscriptionType} to exist for user {$user->id}");
    }

    /**
     * Assert that a subscription is active
     */
    protected function assertSubscriptionIsActive(Subscription $subscription): void
    {
        Assert::assertEquals('active', $subscription->stripe_status, "Expected subscription {$subscription->id} to be active but was {$subscription->stripe_status}");
        Assert::assertNull($subscription->ends_at, "Expected subscription {$subscription->id} to not have an end date");
    }

    /**
     * Assert that a subscription is cancelled
     */
    protected function assertSubscriptionIsCancelled(Subscription $subscription): void
    {
        Assert::assertNotNull($subscription->ends_at, "Expected subscription {$subscription->id} to have an end date");
    }

    /**
     * Assert redirect with error message
     */
    protected function assertRedirectWithError(TestResponse $response, string $errorMessage, string $route = '/dashboard'): void
    {
        $response->assertRedirect($route);
        $response->assertSessionHas('error', $errorMessage);
    }

    /**
     * Assert redirect with success message
     */
    protected function assertRedirectWithSuccess(TestResponse $response, string $successMessage, string $route = '/dashboard'): void
    {
        $response->assertRedirect($route);
        $response->assertSessionHas('success', $successMessage);
    }

    /**
     * Assert redirect to checkout
     */
    protected function assertRedirectToCheckout(TestResponse $response, SteerListing|StudListing $listing): void
    {
        $type = $listing instanceof SteerListing ? '' : '-stud';
        $route = "/subscriptions/checkout{$type}/{$listing->id}";
        $response->assertRedirect($route);
    }

    /**
     * Assert user has Stripe customer ID
     */
    protected function assertUserHasStripeId(User $user): void
    {
        $user->refresh();
        Assert::assertNotNull($user->stripe_id, "Expected user {$user->id} to have a stripe_id");
        Assert::assertStringStartsWith('cus_', $user->stripe_id, "Expected stripe_id to start with 'cus_'");
    }

    /**
     * Assert subscription count for user
     */
    protected function assertSubscriptionCount(User $user, int $expectedCount): void
    {
        $actualCount = $user->subscriptions()->count();
        Assert::assertEquals($expectedCount, $actualCount, "Expected user {$user->id} to have {$expectedCount} subscriptions but has {$actualCount}");
    }
}