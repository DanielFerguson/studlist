<?php

use App\Models\SteerListing;
use App\Models\User;

describe('Steer Listing Creation Integration', function () {
    test('complete user journey from creation to dashboard works correctly', function () {
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

        // Should redirect to dashboard with success message
        $createResponse->assertRedirect()
            ->assertSessionHas('success', 'Steer listing created successfully! Your listing is now live.');

        // Step 2: Verify the listing was created as active
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Premium Angus Steer')
            ->first();

        expect($steer)->not->toBeNull();
        expect($steer->isActive())->toBeTrue();
        expect($steer->isDraft())->toBeFalse();
        expect($steer->breed)->toBe('Angus');
        expect($steer->price)->toBe(2500.00);

        // Step 3: Verify redirect URL points to dashboard
        $createResponse->assertRedirect(route('dashboard'));

        // Step 4: Verify the steer can be found on dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        $dashboardResponse->assertOk()
            ->assertSee('Premium Angus Steer');
    });

    test('listing is immediately active after creation', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Test Steer for Active',
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

        // Verify the listing on dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        $dashboardResponse->assertOk()
            ->assertSee('Test Steer for Active');

        // Verify it's active in the database
        $steer = SteerListing::where('user_id', $user->id)
            ->where('name', 'Test Steer for Active')
            ->first();

        expect($steer)->not->toBeNull();
        expect($steer->isActive())->toBeTrue();
        expect($steer->status)->toBe('active');
    });
});
