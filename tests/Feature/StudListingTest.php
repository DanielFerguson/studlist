<?php

use App\Models\StudListing;
use App\Models\User;
use Laravel\Cashier\Subscription;

describe('Stud Listing Creation', function () {
    test('guests cannot create stud listings', function () {
        $this->get('/studs/create')
            ->assertRedirect('/login');

        $this->post('/studs', [])
            ->assertRedirect('/login');
    });

    test('authenticated users can view the create form', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/studs/create')
            ->assertOk()
            ->assertViewIs('listings.studs.create');
    });

    test('users can create a stud listing with basic data', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud',
            'dob' => '2020-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Your listing is now live.');

        // Verify the listing was created correctly in the database
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Test Stud')
            ->first();

        $this->assertDatabaseHas('stud_listings', [
            'user_id' => $user->id,
            'name' => 'Test Stud',
            'breed' => 'Angus',
            'status' => 'active',
        ]);

        // Verify redirect goes to dashboard
        $response->assertRedirect(route('dashboard'));
    });

    test('users can create a stud listing with stud-specific fields', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Champion Bull',
            'dob' => '2019-05-10',
            'breed' => 'Charolais',
            'colour' => 'White',
            'tattoo_number' => 'CH123',
            'location' => 'Queensland, Australia',
            'registration_link' => 'https://example.com/registration',
            'email_contact' => 'breeder@example.com',
            'phone_contact' => '0412345678',
            'business_contact' => 'Elite Genetics',
            'sire' => 'Grand Champion',
            'dam' => 'Elite Cow',
            'description' => 'Premium stud bull with excellent genetics.',
        ];

        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Your listing is now live.');

        $this->assertDatabaseHas('stud_listings', [
            'user_id' => $user->id,
            'name' => 'Champion Bull',
            'tattoo_number' => 'CH123',
            'registration_link' => 'https://example.com/registration',
            'business_contact' => 'Elite Genetics',
            'status' => 'active',
        ]);
    });

    test('creating a stud listing publishes immediately', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud for Publish',
            'dob' => '2020-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        // Should redirect to dashboard
        $response->assertRedirect()
            ->assertSessionHas('success', 'Stud listing created successfully! Your listing is now live.');

        // Verify the listing was created as active
        $stud = StudListing::where('user_id', $user->id)
            ->where('name', 'Test Stud for Publish')
            ->first();

        $this->assertNotNull($stud);
        $this->assertTrue($stud->isActive());

        // Verify redirect URL is dashboard
        $response->assertRedirect(route('dashboard'));
    });
});

describe('Stud Listing Viewing', function () {
    test('users can view their own stud listings on dashboard', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($stud->name);
    });

    test('dashboard shows stud listing status correctly', function () {
        $user = User::factory()->create();
        $draftStud = StudListing::factory()->draft()->create(['user_id' => $user->id]);
        $activeStud = StudListing::factory()->active()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee($draftStud->name)
            ->assertSee($activeStud->name);
    });
});

describe('Stud Listing Editing', function () {
    test('guests cannot edit stud listings', function () {
        $stud = StudListing::factory()->create();

        $this->get("/studs/{$stud->id}/edit")
            ->assertRedirect('/login');

        $this->put("/studs/{$stud->id}", [])
            ->assertRedirect('/login');
    });

    test('users can only edit their own stud listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get("/studs/{$stud->id}/edit")
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->put("/studs/{$stud->id}", ['name' => 'Updated Name'])
            ->assertForbidden();
    });

    test('users can view edit form for their own listings', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/studs/{$stud->id}/edit")
            ->assertOk()
            ->assertViewIs('listings.studs.edit')
            ->assertViewHas('stud');
    });

    test('users can update their stud listings', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => 'Updated Stud Name',
            'dob' => '2019-02-20',
            'breed' => 'Hereford',
            'colour' => 'Red',
            'tattoo_number' => 'HF456',
            'location' => 'Updated Location',
            'registration_link' => 'https://updated.example.com',
            'email_contact' => 'updated@example.com',
        ];

        $this->actingAs($user)
            ->put("/studs/{$stud->id}", $updateData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Stud listing updated successfully!');

        $this->assertDatabaseHas('stud_listings', [
            'id' => $stud->id,
            'name' => 'Updated Stud Name',
            'breed' => 'Hereford',
            'tattoo_number' => 'HF456',
        ]);
    });
});

describe('Stud Listing Deletion', function () {
    test('guests cannot delete stud listings', function () {
        $stud = StudListing::factory()->create();

        $this->delete("/studs/{$stud->id}")
            ->assertRedirect('/login');
    });

    test('users can only delete their own stud listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete("/studs/{$stud->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('stud_listings', ['id' => $stud->id]);
    });

    test('users can delete their own stud listings', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/studs/{$stud->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Stud listing deleted successfully!');

        $this->assertSoftDeleted('stud_listings', ['id' => $stud->id]);
    });

    test('deleting a stud listing with an active subscription cancels the subscription first', function () {
        $user = User::factory()->create();

        // Create a stud listing with active status and subscription
        $stud = StudListing::factory()->active()->create([
            'user_id' => $user->id,
            'stripe_subscription_id' => 'sub_test_456',
        ]);

        // Create the corresponding subscription in Laravel's database
        $subscription = $user->subscriptions()->create([
            'type' => 'stud_'.$stud->id,
            'stripe_id' => 'sub_test_456',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);

        // Also create a subscription item (required for Cashier)
        $subscription->items()->create([
            'stripe_id' => 'si_test_456',
            'stripe_product' => 'prod_test',
            'stripe_price' => 'price_test',
            'quantity' => 1,
        ]);

        // Verify the subscription is active before deletion
        expect($subscription->active())->toBeTrue();
        expect($stud->isActive())->toBeTrue();

        // Delete the listing
        $response = $this->actingAs($user)
            ->delete("/studs/{$stud->id}");

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Stud listing deleted successfully! Subscription cancelled.');

        // Verify the listing is soft deleted
        $this->assertSoftDeleted('stud_listings', ['id' => $stud->id]);

        // Verify the subscription was cancelled
        $subscription->refresh();
        expect($subscription->canceled())->toBeTrue();

        // Verify the stud status was updated to cancelled before deletion
        $deletedStud = StudListing::withTrashed()->find($stud->id);
        expect($deletedStud->status)->toBe('cancelled');
    });
});

describe('Stud Listing Status Management', function () {
    test('new stud listings default to draft status', function () {
        $stud = StudListing::factory()->create();

        expect($stud->status)->toBe('draft');
        expect($stud->isDraft())->toBeTrue();
        expect($stud->isActive())->toBeFalse();
    });

    test('stud listing status methods work correctly', function () {
        $draftStud = StudListing::factory()->draft()->create();
        $activeStud = StudListing::factory()->active()->create();
        $cancelledStud = StudListing::factory()->create(['status' => 'cancelled']);

        expect($draftStud->isDraft())->toBeTrue();
        expect($draftStud->isActive())->toBeFalse();

        expect($activeStud->isDraft())->toBeFalse();
        expect($activeStud->isActive())->toBeTrue();

        expect($cancelledStud->isDraft())->toBeFalse();
        expect($cancelledStud->isActive())->toBeFalse();
    });
});

describe('Stud Listing Validation', function () {
    test('stud listing requires either phone or email contact', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud',
            'dob' => '2020-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            // No phone or email contact
        ];

        $this->actingAs($user)
            ->post('/studs', $studData)
            ->assertSessionHasErrors(['phone_contact', 'email_contact']);
    });

    test('stud listing accepts valid registration link', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud',
            'dob' => '2020-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
            'registration_link' => 'https://valid.url.com',
        ];

        $this->actingAs($user)
            ->post('/studs', $studData)
            ->assertRedirect();

        $this->assertDatabaseHas('stud_listings', [
            'registration_link' => 'https://valid.url.com',
        ]);
    });

    test('stud listing rejects invalid registration link', function () {
        $user = User::factory()->create();

        $studData = [
            'name' => 'Test Stud',
            'dob' => '2020-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
            'registration_link' => 'not-a-valid-url',
        ];

        $this->actingAs($user)
            ->post('/studs', $studData)
            ->assertSessionHasErrors(['registration_link']);
    });
});
