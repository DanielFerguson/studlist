<?php

use App\Models\SteerListing;
use App\Models\User;
use Laravel\Cashier\Subscription;

describe('Steer Listing Creation', function () {
    test('guests cannot create steer listings', function () {
        $this->get('/steers/create')
            ->assertRedirect('/login');

        $this->post('/steers', [])
            ->assertRedirect('/login');
    });

    test('authenticated users can view the create form', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/steers/create')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('steer-listings/create'));
    });

    test('users can create a steer listing with basic data', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Test Steer',
            'dob' => '2023-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Steer listing created successfully! Redirecting to checkout...');

        // Verify the listing was created correctly in the database
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Test Steer')
            ->first();

        $this->assertDatabaseHas('steer_listings', [
            'user_id' => $user->id,
            'name' => 'Test Steer',
            'breed' => 'Angus',
            'status' => 'draft',
        ]);

        // Verify redirect goes to checkout
        $response->assertRedirect(route('subscription.checkout', ['steer' => $steer->id]));
    });

    test('creating a steer listing redirects to checkout for immediate subscription', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Test Steer for Checkout',
            'dob' => '2023-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Should redirect to checkout for the newly created steer
        $response->assertRedirect()
            ->assertSessionHas('success', 'Steer listing created successfully! Redirecting to checkout...');

        // Verify the listing was created as a draft
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Test Steer for Checkout')
            ->first();

        $this->assertNotNull($steer);
        $this->assertTrue($steer->isDraft());

        // Verify redirect URL contains the checkout route
        $response->assertRedirect(route('subscription.checkout', ['steer' => $steer->id]));
    });
});

describe('Steer Listing Viewing', function () {
    test('users can view their own steer listings on dashboard', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($steer->name);
    });

    test('dashboard shows steer listing status correctly', function () {
        $user = User::factory()->create();
        $draftSteer = SteerListing::factory()->draft()->create(['user_id' => $user->id]);
        $activeSteer = SteerListing::factory()->active()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee($draftSteer->name)
            ->assertSee($activeSteer->name);
    });
});

describe('Steer Listing Editing', function () {
    test('guests cannot edit steer listings', function () {
        $steer = SteerListing::factory()->create();

        $this->get("/steers/{$steer->id}/edit")
            ->assertRedirect('/login');

        $this->put("/steers/{$steer->id}", [])
            ->assertRedirect('/login');
    });

    test('users can only edit their own steer listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get("/steers/{$steer->id}/edit")
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->put("/steers/{$steer->id}", ['name' => 'Updated Name'])
            ->assertForbidden();
    });

    test('users can view edit form for their own listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/steers/{$steer->id}/edit")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('steer-listings/edit')
                ->has('steer')
            );
    });

    test('users can update their steer listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => 'Updated Steer Name',
            'dob' => '2023-02-20',
            'breed' => 'Hereford',
            'colour' => 'Red',
            'location' => 'Updated Location',
            'email_contact' => 'updated@example.com',
        ];

        $this->actingAs($user)
            ->put("/steers/{$steer->id}", $updateData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Steer listing updated successfully!');

        $this->assertDatabaseHas('steer_listings', [
            'id' => $steer->id,
            'name' => 'Updated Steer Name',
            'breed' => 'Hereford',
        ]);
    });
});

describe('Steer Listing Deletion', function () {
    test('guests cannot delete steer listings', function () {
        $steer = SteerListing::factory()->create();

        $this->delete("/steers/{$steer->id}")
            ->assertRedirect('/login');
    });

    test('users can only delete their own steer listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete("/steers/{$steer->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('steer_listings', ['id' => $steer->id]);
    });

    test('users can delete their own steer listings', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/steers/{$steer->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Steer listing deleted successfully!');

        $this->assertSoftDeleted('steer_listings', ['id' => $steer->id]);
    });

    test('deleting a steer listing with an active subscription cancels the subscription first', function () {
        $user = User::factory()->create();

        // Create a steer listing with active status and subscription
        $steer = SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => 'sub_test_123',
        ]);

        // Create the corresponding subscription in Laravel's database
        $subscription = $user->subscriptions()->create([
            'type' => 'steer_'.$steer->id,
            'stripe_id' => 'sub_test_123',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);

        // Also create a subscription item (required for Cashier)
        $subscription->items()->create([
            'stripe_id' => 'si_test_123',
            'stripe_product' => 'prod_test',
            'stripe_price' => 'price_test',
            'quantity' => 1,
        ]);

        // Verify the subscription is active before deletion
        expect($subscription->active())->toBeTrue();
        expect($steer->isActive())->toBeTrue();

        // Delete the listing
        $response = $this->actingAs($user)
            ->delete("/steers/{$steer->id}");

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Steer listing deleted successfully! Subscription cancelled.');

        // Verify the listing is soft deleted
        $this->assertSoftDeleted('steer_listings', ['id' => $steer->id]);

        // Verify the subscription was cancelled
        $subscription->refresh();
        expect($subscription->canceled())->toBeTrue();

        // Verify the steer status was updated to cancelled before deletion
        $deletedSteer = SteerListing::withTrashed()->find($steer->id);
        expect($deletedSteer->status)->toBe('cancelled');
    });
});

describe('Steer Listing Status Management', function () {
    test('new steer listings default to draft status', function () {
        $steer = SteerListing::factory()->create();

        expect($steer->status)->toBe('draft');
        expect($steer->isDraft())->toBeTrue();
        expect($steer->isActive())->toBeFalse();
    });

    test('steer listing status methods work correctly', function () {
        $draftSteer = SteerListing::factory()->draft()->create();
        $activeSteer = SteerListing::factory()->active()->create();
        $cancelledSteer = SteerListing::factory()->create(['status' => 'cancelled']);

        expect($draftSteer->isDraft())->toBeTrue();
        expect($draftSteer->isActive())->toBeFalse();

        expect($activeSteer->isDraft())->toBeFalse();
        expect($activeSteer->isActive())->toBeTrue();

        expect($cancelledSteer->isDraft())->toBeFalse();
        expect($cancelledSteer->isActive())->toBeFalse();
    });
});
