<?php

use App\Models\SteerListing;
use App\Models\User;

describe('Steer Listing Creation Integration', function () {
    test('complete user journey from creation to checkout works correctly', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Premium Angus Steer',
            'dob' => '2023-03-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Queensland, Australia',
            'email_contact' => 'farmer@example.com',
            'phone_contact' => '0412345678',
            'business_contact' => 'Premium Cattle Co',
            'sire' => 'Champion Bull',
            'dam' => 'Elite Cow',
            'description' => 'High quality steer ready for market.',
            'started_on_feed' => true,
            'price' => 2500.00,
        ];

        // Step 1: Create the steer listing
        $createResponse = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Should redirect to checkout
        $createResponse->assertRedirect()
            ->assertSessionHas('success', 'Steer listing created successfully! Redirecting to checkout...');

        // Step 2: Verify the listing was created as a draft
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Premium Angus Steer')
            ->first();

        expect($steer)->not->toBeNull();
        expect($steer->isDraft())->toBeTrue();
        expect($steer->isActive())->toBeFalse();
        expect($steer->breed)->toBe('Angus');
        expect($steer->price)->toBe(2500.00);

        // Step 3: Verify redirect URL points to checkout
        $createResponse->assertRedirect(route('subscription.checkout', ['steer' => $steer->id]));

        // Step 4: Follow the redirect to checkout (simulating what happens in browser)
        $checkoutResponse = $this->actingAs($user)
            ->get("/subscriptions/checkout/{$steer->id}");

        // In testing environment, should redirect back to dashboard with success message
        $checkoutResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');

        // Step 5: Verify the steer can still be found on dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        $dashboardResponse->assertOk()
            ->assertSee('Premium Angus Steer')
            ->assertSee('Angus');
    });

    test('listing persists even if user navigates away before completing checkout', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Test Steer for Persistence',
            'dob' => '2023-01-10',
            'breed' => 'Hereford',
            'colour' => 'Red',
            'location' => 'Victoria, Australia',
            'email_contact' => 'test@example.com',
        ];

        // Create the listing
        $this->actingAs($user)
            ->post('/steers', $steerData)
            ->assertRedirect();

        // Simulate user navigating away by going directly to dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        // The listing should still be there as a draft
        $dashboardResponse->assertOk()
            ->assertSee('Test Steer for Persistence')
            ->assertSee('Hereford');

        // Verify it's still a draft in the database
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Test Steer for Persistence')
            ->first();

        expect($steer)->not->toBeNull();
        expect($steer->isDraft())->toBeTrue();
        expect($steer->status)->toBe('draft');
    });
});
