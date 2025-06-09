<?php

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Laravel\Cashier\Subscription;

beforeEach(function () {
    // Mock Stripe API key for testing
    Config::set('cashier.key', 'pk_test_fake');
    Config::set('cashier.secret', 'sk_test_fake');
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

        $this->actingAs($otherUser)
            ->post("/subscriptions/checkout/{$steer->id}")
            ->assertForbidden();
    });

    test('users can only checkout draft listings', function () {
        $user = User::factory()->create();
        $activeSteer = SteerListing::factory()->active()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/subscriptions/checkout/{$activeSteer->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'This listing already has an active subscription.');
    });

    test('users can reactivate cancelled listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled',
        ]);

        // Should allow checkout for cancelled listings
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        // Should redirect to dashboard with success message in testing
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });
});

describe('Subscription Success Handling', function () {
    test('subscription success requires valid session id', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/subscriptions/success/{$steer->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'Invalid checkout session.');
    });
});

describe('Subscription Cancellation', function () {
    test('guests cannot cancel subscriptions', function () {
        $steer = SteerListing::factory()->active()->create();

        $this->post("/subscriptions/cancel/{$steer->id}")
            ->assertRedirect('/login');
    });

    test('users can only cancel their own subscriptions', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $steer = SteerListing::factory()->active()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->post("/subscriptions/cancel/{$steer->id}")
            ->assertForbidden();
    });

    test('cannot cancel subscription without stripe subscription id', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => null,
        ]);

        $this->actingAs($user)
            ->post("/subscriptions/cancel/{$steer->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'No active subscription found for this listing.');
    });
});

describe('Billing Portal Access', function () {
    test('guests cannot access billing portal', function () {
        $this->get('/subscriptions/billing-portal')
            ->assertRedirect('/login');
    });

    test('users without stripe id cannot access billing portal', function () {
        $user = User::factory()->create(['stripe_id' => null]);

        $this->actingAs($user)
            ->get('/subscriptions/billing-portal')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'No billing information found.');
    });

    test('users with stripe id can access billing portal', function () {
        $user = User::factory()->create(['stripe_id' => 'cus_test_123']);

        // In testing environment, should redirect to dashboard with success message
        $response = $this->actingAs($user)
            ->get('/subscriptions/billing-portal');

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test billing portal accessed successfully.');
    });
});

describe('Subscription Model Relationships', function () {
    test('steer listing can have subscription relationship', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $user->id]);

        // Test the relationship exists
        expect($steer->laravelSubscription())->toBeNull();

        // Test with subscription ID
        $steer->update(['stripe_subscription_id' => 'sub_test']);
        expect($steer->subscription())->toBeNull(); // No actual subscription exists
    });

    test('steer listing status helper methods work correctly', function () {
        $draftSteer = SteerListing::factory()->draft()->create();
        $activeSteer = SteerListing::factory()->active()->create();

        expect($draftSteer->isDraft())->toBeTrue();
        expect($draftSteer->isActive())->toBeFalse();

        expect($activeSteer->isDraft())->toBeFalse();
        expect($activeSteer->isActive())->toBeTrue();
    });

    test('user can have multiple steer listings', function () {
        $user = User::factory()->create();
        $steers = SteerListing::factory()->count(3)->create(['user_id' => $user->id]);

        expect($user->steerListings())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($user->steerListings)->toHaveCount(3);
    });
});

describe('Subscription Reactivation Logic', function () {
    test('can reactivate cancelled listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled',
        ]);

        // Test the checkout allows cancelled listings
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        // Should redirect to dashboard with success message in testing
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });

    test('cannot checkout already active listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->active()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'This listing already has an active subscription.');
    });

    test('draft listings can be subscribed to', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        // Should redirect to dashboard with success message in testing
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });

    test('checkout route accepts both GET and POST methods', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);

        // Test POST request (existing functionality)
        $postResponse = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        $postResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');

        // Test GET request (new functionality for redirect after creation)
        $getResponse = $this->actingAs($user)
            ->get("/subscriptions/checkout/{$steer->id}");

        $getResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });
});

describe('Stud Listing Subscription Checkout', function () {
    test('guests cannot access stud subscription checkout', function () {
        $stud = StudListing::factory()->create();

        $this->post("/subscriptions/checkout-stud/{$stud->id}")
            ->assertRedirect('/login');
    });

    test('users cannot checkout subscriptions for stud listings they do not own', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->post("/subscriptions/checkout-stud/{$stud->id}")
            ->assertForbidden();
    });

    test('users can only checkout draft stud listings', function () {
        $user = User::factory()->create();
        $activeStud = StudListing::factory()->active()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$activeStud->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'This listing already has an active subscription.');
    });

    test('users can reactivate cancelled stud listings', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled',
        ]);

        // Should allow checkout for cancelled listings
        $response = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        // Should redirect to dashboard with success message in testing
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });

    test('stud checkout route accepts both GET and POST methods', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->draft()->create(['user_id' => $user->id]);

        // Test POST request
        $postResponse = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        $postResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');

        // Test GET request
        $getResponse = $this->actingAs($user)
            ->get("/subscriptions/checkout-stud/{$stud->id}");

        $getResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });
});

describe('Stud Listing Subscription Success Handling', function () {
    test('stud subscription success requires valid session id', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->draft()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/subscriptions/success-stud/{$stud->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'Invalid checkout session.');
    });
});

describe('Stud Listing Subscription Cancellation', function () {
    test('guests cannot cancel stud subscriptions', function () {
        $stud = StudListing::factory()->active()->create();

        $this->post("/subscriptions/cancel-stud/{$stud->id}")
            ->assertRedirect('/login');
    });

    test('users can only cancel their own stud subscriptions', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $stud = StudListing::factory()->active()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->post("/subscriptions/cancel-stud/{$stud->id}")
            ->assertForbidden();
    });

    test('cannot cancel stud subscription without stripe subscription id', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => null,
        ]);

        $this->actingAs($user)
            ->post("/subscriptions/cancel-stud/{$stud->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'No active subscription found for this listing.');
    });

    test('users can cancel their own stud subscriptions', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => 'sub_stud_test_123',
        ]);

        // Create the corresponding subscription in Laravel's database
        $subscription = $user->subscriptions()->create([
            'type' => 'stud_'.$stud->id,
            'stripe_id' => 'sub_stud_test_123',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);

        // Also create a subscription item (required for Cashier)
        $subscription->items()->create([
            'stripe_id' => 'si_stud_test_123',
            'stripe_product' => 'prod_test',
            'stripe_price' => 'price_test',
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->post("/subscriptions/cancel-stud/{$stud->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Subscription cancelled successfully.');

        // Verify subscription was cancelled
        $subscription->refresh();
        expect($subscription->canceled())->toBeTrue();

        // Verify stud status was updated
        $stud->refresh();
        expect($stud->status)->toBe('cancelled');
    });
});

describe('Stud Listing Subscription Model Relationships', function () {
    test('stud listing can have subscription relationship', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $user->id]);

        // Test the relationship exists
        expect($stud->laravelSubscription())->toBeNull();

        // Test with subscription ID
        $stud->update(['stripe_subscription_id' => 'sub_test']);
        expect($stud->subscription())->toBeNull(); // No actual subscription exists
    });

    test('stud listing status helper methods work correctly', function () {
        $draftStud = StudListing::factory()->draft()->create();
        $activeStud = StudListing::factory()->active()->create();

        expect($draftStud->isDraft())->toBeTrue();
        expect($draftStud->isActive())->toBeFalse();

        expect($activeStud->isDraft())->toBeFalse();
        expect($activeStud->isActive())->toBeTrue();
    });

    test('user can have multiple stud listings', function () {
        $user = User::factory()->create();
        $studs = StudListing::factory()->count(3)->create(['user_id' => $user->id]);

        expect($user->studListings())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($user->studListings)->toHaveCount(3);
    });
});

describe('Mixed Steer and Stud Listing Subscriptions', function () {
    test('user can have both steer and stud subscriptions simultaneously', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);
        $stud = StudListing::factory()->draft()->create(['user_id' => $user->id]);

        // Test both can be checked out
        $steerResponse = $this->actingAs($user)
            ->post("/subscriptions/checkout/{$steer->id}");

        $steerResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');

        $studResponse = $this->actingAs($user)
            ->post("/subscriptions/checkout-stud/{$stud->id}");

        $studResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');
    });

    test('dashboard shows both steer and stud listings correctly', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Steer',
        ]);
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Stud',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Test Steer')
            ->assertSee('Test Stud');
    });
});
