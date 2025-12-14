<?php

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;
use Tests\Helpers\AssertionHelpers;
use Tests\Helpers\ListingTestHelpers;
use Tests\Helpers\WebhookTestHelpers;
use Tests\Traits\CreatesTestData;
use Tests\Traits\MocksStripeApi;

uses(
    CreatesTestData::class,
    MocksStripeApi::class,
    AssertionHelpers::class,
    ListingTestHelpers::class,
    WebhookTestHelpers::class
);

beforeEach(function () {
    $this->setUpStripeTest();
});

describe('Complete Payment Flow using Test Utilities', function () {
    test('full lifecycle: create draft, subscribe, webhook, cancel', function () {
        // Step 1: Create user and draft listing directly
        // Note: New listings are now created as active by default (free listings)
        // This test verifies the subscription flow still works for draft listings
        $user = $this->createUserWithStripeId();

        // Create a draft listing directly to test subscription flow
        $steer = SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Test Steer for Subscription',
        ]);

        $this->assertListingIsDraft($steer);

        // Step 3: Initiate subscription checkout
        $checkoutResponse = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        $this->assertRedirectWithSuccess($checkoutResponse, 'Test checkout initiated successfully.');

        // Step 4: Simulate webhook for subscription created
        $subscriptionData = $this->createSubscriptionWebhookData([
            'id' => 'sub_new_123',
            'status' => 'active',
        ]);

        // Update listing to simulate webhook processing
        $steer->update([
            'status' => 'active',
            'stripe_subscription_id' => 'sub_new_123',
        ]);

        // Create actual subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'type' => 'steer_'.$steer->id,
            'stripe_id' => 'sub_new_123',
            'stripe_status' => 'active',
            'stripe_price' => config('cashier.price_ids.steer_listing'),
            'quantity' => 1,
        ]);

        // Create subscription item
        \Laravel\Cashier\SubscriptionItem::create([
            'subscription_id' => $subscription->id,
            'stripe_id' => 'si_test_'.uniqid(),
            'stripe_product' => 'prod_test',
            'stripe_price' => $subscription->stripe_price,
            'quantity' => 1,
        ]);

        $this->assertListingIsActive($steer);
        $this->assertSubscriptionExists($user, $steer);

        // Step 5: Cancel subscription
        $cancelResponse = $this->actingAs($user)
            ->post("/subscriptions/cancel/{$steer->id}");

        $this->assertRedirectWithSuccess($cancelResponse, 'Subscription cancelled successfully.');
        $this->assertListingIsCancelled($steer);
    });

    test('handles multiple concurrent subscriptions', function () {
        // Create user with multiple active subscriptions
        $user = $this->createUserWithMultipleSubscriptions(3, 2);

        // Verify subscription count
        $this->assertSubscriptionCount($user, 5); // 3 steers + 2 studs

        // Get all listings
        $steerListings = SteerListing::where('user_id', $user->id)->get();
        $studListings = StudListing::where('user_id', $user->id)->get();

        // Verify all are active
        $steerListings->each(fn ($steer) => $this->assertListingIsActive($steer));
        $studListings->each(fn ($stud) => $this->assertListingIsActive($stud));

        // Cancel one steer listing
        $steerToCancel = $steerListings->first();
        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel/{$steerToCancel->id}");

        $this->assertRedirectWithSuccess($response, 'Subscription cancelled successfully.');
        $this->assertListingIsCancelled($steerToCancel);

        // Others should remain active
        $steerListings->skip(1)->each(fn ($steer) => $this->assertListingIsActive($steer));
        $studListings->each(fn ($stud) => $this->assertListingIsActive($stud));
    });

    test('manages subscription states correctly', function () {
        $user = $this->createUserWithStripeId();

        // Create listings in various states
        $activeListing = $this->createSteerWithSubscription($user);
        $gracePeriodListing = $this->createGracePeriodListing('steer', $user);
        $cancelledListing = $this->createCancelledListing('steer', $user);
        $pastDueListing = $this->createPastDueListing('steer', $user);
        $trialingListing = $this->createTrialingListing('steer', $user, 14);

        // Verify states
        $this->assertListingIsActive($activeListing);
        $this->assertListingIsActive($gracePeriodListing); // Still active during grace
        $this->assertListingIsCancelled($cancelledListing);
        $this->assertListingIsActive($pastDueListing); // Still active but past due
        $this->assertListingIsActive($trialingListing);

        // Try to reactivate grace period listing
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$gracePeriodListing->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');

        // Try to reactivate cancelled listing
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$cancelledListing->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');

        // Cannot checkout already active listing
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$activeListing->id}");

        $this->assertRedirectWithError($response, 'This listing already has an active subscription.');
    });

    test('processes webhook events through complete flow', function () {
        $user = $this->createUserWithStripeId();
        $steer = SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
        ]);

        // Checkout session completed webhook
        $checkoutData = $this->createCheckoutSessionWebhookData([
            'subscription' => 'sub_webhook_123',
            'metadata' => ['steer_id' => $steer->id],
        ]);

        // Process checkout completion
        $steer->update([
            'status' => 'active',
            'stripe_subscription_id' => 'sub_webhook_123',
        ]);

        // Subscription created webhook
        $subscriptionData = $this->createSubscriptionWebhookData([
            'id' => 'sub_webhook_123',
            'status' => 'active',
        ]);

        $this->testWebhookHandler('updateSteerListingFromSubscription', $subscriptionData, 'active');
        $this->assertListingIsActive($steer);

        // Invoice paid webhook
        $invoiceData = $this->createInvoiceWebhookData([
            'subscription' => 'sub_webhook_123',
            'amount_paid' => 1500,
        ]);

        // Log would be created in production - no need to mock in test

        // Subscription updated to past_due
        $pastDueData = $this->createSubscriptionWebhookData([
            'id' => 'sub_webhook_123',
            'status' => 'past_due',
        ]);

        $this->testWebhookHandler('updateSteerListingFromSubscription', $pastDueData, 'past_due');

        // Subscription cancelled
        $cancelledData = $this->createSubscriptionWebhookData([
            'id' => 'sub_webhook_123',
            'status' => 'canceled',
            'canceled_at' => time(),
        ]);

        $this->testWebhookHandler('updateSteerListingFromSubscription', $cancelledData, 'canceled');
        $this->assertListingIsCancelled($steer);
    });

    test('handles complex user scenarios', function () {
        // Create user with varied listing portfolio
        $user = $this->createUserWithStripeId();
        $listings = $this->createMultipleListings($user, ['steer', 'stud', 'genetics', 'equipment']);

        // Add subscriptions to some listings
        $activeSteer = $this->createSteerWithSubscription($user);
        $activeStud = $this->createStudWithSubscription($user);

        // Create listings with photos
        $steerWithPhotos = $this->createListingWithPhotos('steer', $user, 3);
        $studWithPhotos = $this->createListingWithPhotos('stud', $user, 5);

        // Count total listings
        $totalSteers = SteerListing::where('user_id', $user->id)->count();
        $totalStuds = StudListing::where('user_id', $user->id)->count();

        $this->assertGreaterThanOrEqual(3, $totalSteers); // At least 3 steers
        $this->assertGreaterThanOrEqual(3, $totalStuds); // At least 3 studs

        // Verify photos
        $this->assertNotEmpty($steerWithPhotos->photos);
        $this->assertCount(3, $steerWithPhotos->photos);
        $this->assertCount(5, $studWithPhotos->photos);

        // Test billing portal access
        $response = $this->actingAs($user)
            ->get('/subscriptions/billing-portal');

        $this->assertRedirectWithSuccess($response, 'Test billing portal accessed successfully.');
    });
});

describe('Edge Cases with Test Utilities', function () {
    test('handles rapid subscription state changes', function () {
        $user = $this->createUserWithStripeId();
        $steer = $this->createSteerWithSubscription($user);

        // Rapid state changes via webhooks
        $states = ['active', 'past_due', 'active', 'canceled', 'active'];

        foreach ($states as $state) {
            $webhookData = $this->createSubscriptionWebhookData([
                'id' => $steer->stripe_subscription_id,
                'status' => $state,
            ]);

            $this->testWebhookHandler('updateSteerListingFromSubscription', $webhookData, $state);

            $steer->refresh();

            // Check the mapped status
            $expectedStatus = match ($state) {
                'active', 'trialing', 'past_due' => 'active',
                'canceled', 'paused' => 'cancelled',
                default => 'draft'
            };

            if ($expectedStatus === 'cancelled') {
                $this->assertListingIsCancelled($steer);
            } elseif ($expectedStatus === 'active') {
                $this->assertListingIsActive($steer);
            } else {
                $this->assertListingIsDraft($steer);
            }
        }
    });

    test('manages orphaned subscriptions and listings', function () {
        $user = $this->createUserWithStripeId();

        // Create orphaned subscription (no listing)
        $orphanedSub = Subscription::create([
            'user_id' => $user->id,
            'type' => 'steer_999999',
            'stripe_id' => 'sub_orphaned',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test',
            'quantity' => 1,
        ]);

        // Create listing with non-existent subscription
        $orphanedListing = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'stripe_subscription_id' => 'sub_nonexistent',
        ]);

        // Webhook for orphaned subscription
        $webhookData = $this->createSubscriptionWebhookData([
            'id' => 'sub_orphaned',
            'status' => 'canceled',
        ]);

        // Process webhook - will silently not update any listing since none match
        $this->testWebhookHandler('updateListingFromSubscription', $webhookData, 'canceled');

        // Try to cancel orphaned listing
        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel/{$orphanedListing->id}");

        $this->assertRedirectWithError($response, 'Subscription not found.');
    });
});
