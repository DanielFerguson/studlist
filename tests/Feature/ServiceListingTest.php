<?php

use App\Models\ServiceListing;
use App\Models\User;

describe('Service Listing Creation', function () {
    it('guests cannot create service listings', function () {
        $this->post(route('services.store'), [])
            ->assertRedirect(route('login'));
    });

    it('authenticated users can view the create form', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('services.create'))
            ->assertOk()
            ->assertViewIs('listings.services.create');
    });

    it('users can create a service listing with basic data', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Fitting'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'phone_contact' => '0412345678',
                'locations_covered' => ['NSW', 'VIC'],
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('service_listings', [
            'user_id' => $user->id,
            'business_name' => 'Test Business',
            'contact_name' => 'John Smith',
        ]);

        $listing = ServiceListing::where('user_id', $user->id)->firstOrFail();
        expect($listing->types)->toContain('Fitting');
    });

    it('users can create a service listing with all fields', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Photography'],
                'abn' => '12 345 678 901',
                'business_name' => 'Complete Photography',
                'contact_name' => 'Jane Doe',
                'phone_contact' => '0498765432',
                'email_contact' => 'jane@photography.com',
                'locations_covered' => ['QLD', 'NSW', 'VIC'],
                'links' => ['https://example.com'],
                'description' => 'Professional cattle photography services.',
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('service_listings', [
            'user_id' => $user->id,
            'business_name' => 'Complete Photography',
            'contact_name' => 'Jane Doe',
            'email_contact' => 'jane@photography.com',
        ]);

        $listing = ServiceListing::where('user_id', $user->id)->firstOrFail();
        expect($listing->types)->toContain('Photography');
    });

    it('creating service listing redirects to dashboard without subscription', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Feed Supplier'],
                'business_name' => 'Feed Services',
                'contact_name' => 'Bob Builder',
                'phone_contact' => '0411111111',
                'locations_covered' => ['SA'],
            ]);

        // Service listings are free, so should redirect to dashboard
        $response->assertRedirect(route('dashboard'));
    });
});

describe('Service Listing Viewing', function () {
    it('users can view their own service listings on dashboard', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'My Service Business',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('My Service Business');
    });

    it('dashboard displays service listing details correctly', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'types' => ['Fitting', 'Photography'],
            'business_name' => 'Premium Fitting',
            'contact_name' => 'Expert Fitter',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Premium Fitting');
    });
});

describe('Service Listing Editing', function () {
    it('guests cannot edit service listings', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create(['user_id' => $user->id]);

        $this->get(route('services.edit', $listing))
            ->assertRedirect(route('login'));
    });

    it('users can only edit their own service listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get(route('services.edit', $listing))
            ->assertForbidden();
    });

    it('users can view edit form for their own listings', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'Editable Service',
        ]);

        $this->actingAs($user)
            ->get(route('services.edit', $listing))
            ->assertOk()
            ->assertViewIs('listings.services.edit');
    });

    it('users can update their service listings', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'Original Name',
        ]);

        $this->actingAs($user)
            ->put(route('services.update', $listing), [
                'types' => ['Fitting', 'Photography'],
                'business_name' => 'Updated Business Name',
                'contact_name' => $listing->contact_name,
                'phone_contact' => $listing->phone_contact,
                'locations_covered' => $listing->locations_covered,
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('service_listings', [
            'id' => $listing->id,
            'business_name' => 'Updated Business Name',
        ]);
    });
});

describe('Service Listing Deletion', function () {
    it('guests cannot delete service listings', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create(['user_id' => $user->id]);

        $this->delete(route('services.destroy', $listing))
            ->assertRedirect(route('login'));
    });

    it('users can only delete their own service listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete(route('services.destroy', $listing))
            ->assertForbidden();

        $this->assertDatabaseHas('service_listings', ['id' => $listing->id]);
    });

    it('users can delete their own service listings', function () {
        $user = User::factory()->create();
        $listing = ServiceListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('services.destroy', $listing))
            ->assertRedirect(route('dashboard'));

        $this->assertSoftDeleted('service_listings', ['id' => $listing->id]);
    });
});

describe('Service Listing Validation', function () {
    it('service listing requires either phone or email contact', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Fitting'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'locations_covered' => ['NSW'],
                // No phone or email
            ])
            ->assertSessionHasErrors();
    });

    it('service listing validates type enum', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['InvalidType'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'phone_contact' => '0412345678',
                'locations_covered' => ['NSW'],
            ])
            ->assertSessionHasErrors('types.0');
    });

    it('service listing validates required fields', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [])
            ->assertSessionHasErrors(['types', 'business_name', 'contact_name', 'locations_covered']);
    });

    it('service listing accepts valid type values', function () {
        $user = User::factory()->create();

        $validTypes = ['Clipping', 'Fitting', 'Photography', 'Transport', 'Veterinary', 'Feed Supplier', 'Show Preparation', 'Other'];

        foreach ($validTypes as $type) {
            $response = $this->actingAs($user)
                ->post(route('services.store'), [
                    'types' => [$type],
                    'business_name' => "Test {$type} Business",
                    'contact_name' => 'John Smith',
                    'phone_contact' => '0412345678',
                    'locations_covered' => ['NSW'],
                ]);

            $response->assertSessionDoesntHaveErrors();
        }
    });

    it('service listing validates locations_covered is an array', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Fitting'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'phone_contact' => '0412345678',
                'locations_covered' => 'NSW', // Should be array
            ])
            ->assertSessionHasErrors('locations_covered');
    });

    it('service listing validates email format', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Fitting'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'email_contact' => 'invalid-email',
                'locations_covered' => ['NSW'],
            ])
            ->assertSessionHasErrors('email_contact');
    });

    it('service listing accepts valid email format', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), [
                'types' => ['Fitting'],
                'business_name' => 'Test Business',
                'contact_name' => 'John Smith',
                'email_contact' => 'valid@example.com',
                'locations_covered' => ['NSW'],
            ])
            ->assertSessionDoesntHaveErrors('email_contact');
    });
});
