<?php

use App\Models\GeneticsListing;
use App\Models\User;

describe('Genetics Listing Creation', function () {
    test('guests cannot create genetics listings', function () {
        $this->get('/genetics/create')
            ->assertRedirect('/login');

        $this->post('/genetics', [])
            ->assertRedirect('/login');
    });

    test('authenticated users can view the create form', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/genetics/create')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('genetics-listings/create'));
    });

    test('users can create a genetics listing with basic data', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Premium Angus Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            'email_contact' => 'genetics@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/genetics', $geneticsData);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Genetics listing created successfully!');

        // Verify the listing was created correctly in the database
        $genetics = GeneticsListing::where('user_id', $user->id)
            ->where('name', 'Premium Angus Genetics')
            ->first();

        $this->assertDatabaseHas('genetics_listings', [
            'user_id' => $user->id,
            'name' => 'Premium Angus Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
        ]);

        $this->assertNotNull($genetics);
    });

    test('users can create a genetics listing with all fields', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Elite Charolais Embryos',
            'price' => 1500,
            'breed' => 'Charolais',
            'type' => 'Embryos',
            'storage_location' => 'QLD',
            'sire' => 'Champion Bull CH001',
            'dam' => 'Elite Cow EC002',
            'registration_link' => 'https://registration.example.com/genetics',
            'phone_contact' => '0412345678',
            'email_contact' => 'elite@genetics.com',
            'description' => 'Premium genetics from champion bloodlines with excellent performance records.',
        ];

        $response = $this->actingAs($user)
            ->post('/genetics', $geneticsData);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Genetics listing created successfully!');

        $this->assertDatabaseHas('genetics_listings', [
            'user_id' => $user->id,
            'name' => 'Elite Charolais Embryos',
            'price' => 1500,
            'type' => 'Embryos',
            'sire' => 'Champion Bull CH001',
            'dam' => 'Elite Cow EC002',
            'registration_link' => 'https://registration.example.com/genetics',
        ]);
    });

    test('creating genetics listing redirects to dashboard without subscription', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 300,
            'breed' => 'Hereford',
            'type' => 'Semen Straws',
            'storage_location' => 'VIC',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/genetics', $geneticsData);

        // Should redirect directly to dashboard (no checkout like steers/studs)
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Genetics listing created successfully!');

        // Verify the listing was created and is immediately available (no draft status)
        $genetics = GeneticsListing::where('user_id', $user->id)
            ->where('name', 'Test Genetics')
            ->first();

        $this->assertNotNull($genetics);
    });
});

describe('Genetics Listing Viewing', function () {
    test('users can view their own genetics listings on dashboard', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($genetics->name);
    });

    test('dashboard displays genetics listing details correctly', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Premium Brahman Genetics',
            'type' => 'Embryos',
            'price' => 800,
            'storage_location' => 'QLD',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Premium Brahman Genetics')
            ->assertSee('Embryos')
            ->assertSee('800')
            ->assertSee('QLD');
    });
});

describe('Genetics Listing Editing', function () {
    test('guests cannot edit genetics listings', function () {
        $genetics = GeneticsListing::factory()->create();

        $this->get("/genetics/{$genetics->id}/edit")
            ->assertRedirect('/login');

        $this->put("/genetics/{$genetics->id}", [])
            ->assertRedirect('/login');
    });

    test('users can only edit their own genetics listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get("/genetics/{$genetics->id}/edit")
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->put("/genetics/{$genetics->id}", [
                'name' => 'Updated Name',
                'price' => 500,
                'breed' => 'Angus',
                'type' => 'Semen Straws',
                'storage_location' => 'NSW',
                'email_contact' => 'test@example.com',
            ])
            ->assertForbidden();
    });

    test('users can view edit form for their own listings', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/genetics/{$genetics->id}/edit")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('genetics-listings/edit')
                ->has('genetics')
            );
    });

    test('users can update their genetics listings', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => 'Updated Genetics Name',
            'price' => 1200,
            'breed' => 'Charolais',
            'type' => 'Embryos',
            'storage_location' => 'SA',
            'sire' => 'Updated Sire',
            'dam' => 'Updated Dam',
            'registration_link' => 'https://updated.registration.com',
            'phone_contact' => '0423456789',
            'email_contact' => 'updated@genetics.com',
            'description' => 'Updated description for genetics.',
        ];

        $this->actingAs($user)
            ->put("/genetics/{$genetics->id}", $updateData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Genetics listing updated successfully!');

        $this->assertDatabaseHas('genetics_listings', [
            'id' => $genetics->id,
            'name' => 'Updated Genetics Name',
            'price' => 1200,
            'breed' => 'Charolais',
            'type' => 'Embryos',
            'storage_location' => 'SA',
        ]);
    });
});

describe('Genetics Listing Deletion', function () {
    test('guests cannot delete genetics listings', function () {
        $genetics = GeneticsListing::factory()->create();

        $this->delete("/genetics/{$genetics->id}")
            ->assertRedirect('/login');
    });

    test('users can only delete their own genetics listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete("/genetics/{$genetics->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('genetics_listings', ['id' => $genetics->id]);
    });

    test('users can delete their own genetics listings', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/genetics/{$genetics->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Genetics listing deleted successfully!');

        $this->assertSoftDeleted('genetics_listings', ['id' => $genetics->id]);
    });
});

describe('Genetics Listing Validation', function () {
    test('genetics listing requires either phone or email contact', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            // No phone or email contact
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['phone_contact', 'email_contact']);
    });

    test('genetics listing validates type enum', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Invalid Type',
            'storage_location' => 'NSW',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['type']);
    });

    test('genetics listing validates storage location enum', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'Invalid State',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['storage_location']);
    });

    test('genetics listing validates required fields', function () {
        $user = User::factory()->create();

        // Missing required fields
        $geneticsData = [
            'description' => 'Just a description',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['name', 'price', 'breed', 'type', 'storage_location']);
    });

    test('genetics listing accepts valid type values', function () {
        $user = User::factory()->create();
        $validTypes = ['Semen Straws', 'Embryos'];

        foreach ($validTypes as $type) {
            $geneticsData = [
                'name' => "Test Genetics - {$type}",
                'price' => 500,
                'breed' => 'Angus',
                'type' => $type,
                'storage_location' => 'NSW',
                'email_contact' => 'test@example.com',
            ];

            $this->actingAs($user)
                ->post('/genetics', $geneticsData)
                ->assertRedirect('/dashboard');

            $this->assertDatabaseHas('genetics_listings', [
                'name' => "Test Genetics - {$type}",
                'type' => $type,
            ]);
        }
    });

    test('genetics listing accepts valid storage location values', function () {
        $user = User::factory()->create();
        $validStates = ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA'];

        foreach ($validStates as $state) {
            $geneticsData = [
                'name' => "Test Genetics - {$state}",
                'price' => 500,
                'breed' => 'Angus',
                'type' => 'Semen Straws',
                'storage_location' => $state,
                'email_contact' => 'test@example.com',
            ];

            $this->actingAs($user)
                ->post('/genetics', $geneticsData)
                ->assertRedirect('/dashboard');

            $this->assertDatabaseHas('genetics_listings', [
                'name' => "Test Genetics - {$state}",
                'storage_location' => $state,
            ]);
        }
    });

    test('genetics listing validates price is numeric', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 'not-a-number',
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['price']);
    });

    test('genetics listing validates registration link format', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            'registration_link' => 'not-a-valid-url',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertSessionHasErrors(['registration_link']);
    });

    test('genetics listing accepts valid registration link', function () {
        $user = User::factory()->create();

        $geneticsData = [
            'name' => 'Test Genetics',
            'price' => 500,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            'registration_link' => 'https://valid.registration.com',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('genetics_listings', [
            'registration_link' => 'https://valid.registration.com',
        ]);
    });
});
