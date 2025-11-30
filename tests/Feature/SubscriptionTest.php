<?php

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Tests\Helpers\AssertionHelpers;
use Tests\Traits\CreatesTestData;
use Tests\Traits\MocksStripeApi;

uses(CreatesTestData::class, MocksStripeApi::class, AssertionHelpers::class);

beforeEach(function () {
    $this->setUpStripeTest();
});

describe('Subscription Checkout', function () {
    test('guests cannot access subscription checkout', function () {
        $steer = SteerListing::factory()->create();

        $this->post("/subscriptions/checkout/{$steer->id}")
            ->assertRedirect('/login');
    });

    test('users cannot checkout subscriptions for listings they do not own', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)
            ->post("/subscriptions/checkout/{$steer->id}");

        $response->assertForbidden();
    });

    test('users can only checkout draft listings', function () {
        $user = $this->createUserWithStripeId();
        $activeSteer = $this->createSteerWithSubscription($user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$activeSteer->id}");

        $this->assertRedirectWithError($response, 'This listing already has an active subscription.');
    });

    test('users can reactivate cancelled listings', function () {
        $user = $this->createUserWithStripeId();
        $steer = $this->createCancelledListing('steer', $user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');
    });
});

describe('Subscription Success Handling', function () {
    test('subscription success requires valid session id', function () {
        $user = $this->createUserWithStripeId();
        $steer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get("/subscriptions/success/{$steer->id}");

        $this->assertRedirectWithError($response, 'Invalid checkout session.');
    });
});

describe('Subscription Cancellation', function () {
    test('guests cannot cancel subscriptions', function () {
        $steer = $this->createSteerWithSubscription();

        $this->post("/subscriptions/cancel/{$steer->id}")
            ->assertRedirect('/login');
    });

    test('users can only cancel their own subscriptions', function () {
        $owner = $this->createUserWithStripeId();
        $otherUser = $this->createUserWithStripeId();
        $steer = $this->createSteerWithSubscription($owner);

        $response = $this->actingAs($otherUser)
            ->post("/subscriptions/cancel/{$steer->id}");

        $response->assertForbidden();
    });

    test('cannot cancel subscription without stripe subscription id', function () {
        $user = $this->createUserWithStripeId();
        $steer = SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => null,
        ]);

        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel/{$steer->id}");

        $this->assertRedirectWithError($response, 'No active subscription found for this listing.');
    });

    test('users can cancel active subscriptions', function () {
        $user = $this->createUserWithStripeId();
        $steer = $this->createSteerWithSubscription($user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel/{$steer->id}");

        $this->assertRedirectWithSuccess($response, 'Subscription cancelled successfully.');
        $this->assertListingIsCancelled($steer);
    });
});

describe('Billing Portal', function () {
    test('guests cannot access billing portal', function () {
        $this->get('/subscriptions/billing-portal')
            ->assertRedirect('/login');
    });

    test('users without stripe id cannot access billing portal', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/subscriptions/billing-portal');

        $this->assertRedirectWithError($response, 'No billing information found.');
    });

    test('users with stripe id can access billing portal', function () {
        $user = $this->createUserWithStripeId();

        $response = $this->actingAs($user)
            ->get('/subscriptions/billing-portal');

        // In test environment, returns success message
        $this->assertRedirectWithSuccess($response, 'Test billing portal accessed successfully.');
    });
});

describe('Stud Subscription Checkout', function () {
    test('users can checkout stud listings', function () {
        $user = $this->createUserWithStripeId();
        $stud = StudListing::factory()->draft()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');
    });

    test('users cannot checkout active stud listings', function () {
        $user = $this->createUserWithStripeId();
        $stud = $this->createStudWithSubscription($user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        $this->assertRedirectWithError($response, 'This listing already has an active subscription.');
    });

    test('users can reactivate cancelled stud listings', function () {
        $user = $this->createUserWithStripeId();
        $stud = $this->createCancelledListing('stud', $user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');
    });
});

describe('Stud Subscription Cancellation', function () {
    test('users can cancel stud subscriptions', function () {
        $user = $this->createUserWithStripeId();
        $stud = $this->createStudWithSubscription($user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel-stud/{$stud->id}");

        $this->assertRedirectWithSuccess($response, 'Subscription cancelled successfully.');
        $this->assertListingIsCancelled($stud);
    });

    test('cannot cancel stud subscription without stripe subscription id', function () {
        $user = $this->createUserWithStripeId();
        $stud = StudListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => null,
        ]);

        $response = $this->actingAs($user)
            ->post("/subscriptions/cancel-stud/{$stud->id}");

        $this->assertRedirectWithError($response, 'No active subscription found for this listing.');
    });
});

describe('Subscription Reactivation Edge Cases', function () {
    test('can reactivate listing on grace period', function () {
        $user = $this->createUserWithStripeId();
        $listing = $this->createGracePeriodListing('steer', $user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$listing->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');
    });

    test('can reactivate stud listing on grace period', function () {
        $user = $this->createUserWithStripeId();
        $listing = $this->createGracePeriodListing('stud', $user);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$listing->id}");

        $this->assertRedirectWithSuccess($response, 'Test checkout initiated successfully.');
    });
});
