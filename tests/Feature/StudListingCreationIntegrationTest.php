<?php

use App\Models\StudListing;
use App\Models\User;

describe('Stud Listing Creation Integration', function () {
    test('complete user journey from creation to checkout works correctly', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Elite Angus Bull',
            'dob' => '2018-03-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'tattoo_number' => 'EA123',
            'location' => 'Queensland, Australia',
            'registration_link' => 'https://example.com/registration/EA123',
            'email_contact' => 'elite@example.com',
            'phone_contact' => '0412345678',
            'business_contact' => 'Elite Genetics Co',
            'sire' => 'Champion Bull',
            'dam' => 'Elite Cow',
            'description' => 'Top quality proven sire with excellent genetics.',
        ];

        // Step 1: Create the stud listing
        $createResponse = $this->actingAs($user)
            ->post('/studs', $studData);

        // Should redirect to checkout
        $createResponse->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Redirecting to checkout...');

        // Step 2: Verify the listing was created as a draft
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Elite Angus Bull')
            ->first();

        expect($stud)->not->toBeNull();
        expect($stud->isDraft())->toBeTrue();
        expect($stud->isActive())->toBeFalse();
        expect($stud->breed)->toBe('Angus');
        expect($stud->tattoo_number)->toBe('EA123');
        expect($stud->registration_link)->toBe('https://example.com/registration/EA123');

        // Step 3: Verify redirect URL points to checkout
        $createResponse->assertRedirect(route('subscription.checkout-stud', ['stud' => $stud->id]));

        // Step 4: Follow the redirect to checkout (simulating what happens in browser)
        $checkoutResponse = $this->actingAs($user)
            ->get("/subscriptions/checkout-stud/{$stud->id}");

        // In testing environment, should redirect back to dashboard with success message
        $checkoutResponse->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Test checkout initiated successfully.');

        // Step 5: Verify the stud can still be found on dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        $dashboardResponse->assertOk()
            ->assertSee('Elite Angus Bull');
    });

    test('listing persists even if user navigates away before completing checkout', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud for Persistence',
            'dob' => '2019-01-10',
            'breed' => 'Hereford',
            'colour' => 'Red',
            'tattoo_number' => 'HF789',
            'location' => 'Victoria, Australia',
            'email_contact' => 'test@example.com',
        ];

        // Create the listing
        $this->actingAs($user)
            ->post('/studs', $studData)
            ->assertRedirect();

        // Simulate user navigating away by going directly to dashboard
        $dashboardResponse = $this->actingAs($user)
            ->get('/dashboard');

        // The listing should still be there as a draft
        $dashboardResponse->assertOk()
            ->assertSee('Test Stud for Persistence');

        // Verify it's still a draft in the database
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Test Stud for Persistence')
            ->first();

        expect($stud)->not->toBeNull();
        expect($stud->isDraft())->toBeTrue();
        expect($stud->status)->toBe('draft');
        expect($stud->tattoo_number)->toBe('HF789');
    });

    test('stud listing creation with minimal required data works', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Minimal Bull',
            'dob' => '2021-06-15',
            'breed' => 'Charolais',
            'colour' => 'White',
            'location' => 'NSW, Australia',
            'phone_contact' => '0498765432', // Only phone, no email
        ];

        // Create the listing
        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Redirecting to checkout...');

        // Verify the listing was created
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Minimal Bull')
            ->first();

        expect($stud)->not->toBeNull();
        expect($stud->breed)->toBe('Charolais');
        expect($stud->phone_contact)->toBe('0498765432');
        expect($stud->email_contact)->toBeNull();
        expect($stud->tattoo_number)->toBeNull();
        expect($stud->registration_link)->toBeNull();
    });

    test('stud listing creation with email only works', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Email Only Bull',
            'dob' => '2020-12-01',
            'breed' => 'Limousin',
            'colour' => 'Brown',
            'location' => 'WA, Australia',
            'email_contact' => 'breeder@example.com', // Only email, no phone
        ];

        // Create the listing
        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Redirecting to checkout...');

        // Verify the listing was created
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Email Only Bull')
            ->first();

        expect($stud)->not->toBeNull();
        expect($stud->breed)->toBe('Limousin');
        expect($stud->email_contact)->toBe('breeder@example.com');
        expect($stud->phone_contact)->toBeNull();
    });

    test('user can edit stud listing after creation', function () {
        $user = User::factory()->create();

        // First create a stud listing
        $studData = [
            'name' => 'Original Bull Name',
            'dob' => '2019-05-20',
            'breed' => 'Wagyu',
            'colour' => 'Black',
            'location' => 'Tasmania, Australia',
            'email_contact' => 'original@example.com',
        ];

        $this->actingAs($user)
            ->post('/studs', $studData)
            ->assertRedirect();

        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Original Bull Name')
            ->first();

        // Now edit the listing
        $updateData = [
            'name' => 'Updated Bull Name',
            'dob' => '2019-05-20',
            'breed' => 'Wagyu',
            'colour' => 'Red',
            'tattoo_number' => 'WG999',
            'location' => 'Tasmania, Australia',
            'registration_link' => 'https://new.registry.com/WG999',
            'email_contact' => 'updated@example.com',
            'description' => 'Updated description with new information.',
        ];

        $this->actingAs($user)
            ->put("/studs/{$stud->id}", $updateData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Stud listing updated successfully!');

        // Verify the updates
        $stud->refresh();
        expect($stud->name)->toBe('Updated Bull Name');
        expect($stud->colour)->toBe('Red');
        expect($stud->tattoo_number)->toBe('WG999');
        expect($stud->registration_link)->toBe('https://new.registry.com/WG999');
        expect($stud->description)->toBe('Updated description with new information.');
    });
});
