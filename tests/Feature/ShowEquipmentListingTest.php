<?php

use App\Models\ShowEquipmentListing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Show Equipment Listing Creation', function () {
    test('guests cannot create show equipment listings', function () {
        $this->get('/show-equipment/create')
            ->assertRedirect('/login');

        $this->post('/show-equipment', [])
            ->assertRedirect('/login');
    });

    test('authenticated users can view the create form', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/show-equipment/create')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('show-equipment-listings/create'));
    });

    test('users can create a show equipment listing with basic data', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Professional Show Halter',
            'condition' => 'Like New',
            'location' => 'Tamworth, NSW',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/show-equipment', $equipmentData);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Show equipment listing created successfully!');

        // Verify the listing was created correctly in the database
        $equipment = ShowEquipmentListing::where('user_id', $user->id)
            ->where('title', 'Professional Show Halter')
            ->first();

        $this->assertDatabaseHas('show_equipment_listings', [
            'user_id' => $user->id,
            'title' => 'Professional Show Halter',
            'condition' => 'Like New',
            'location' => 'Tamworth, NSW',
            'email_contact' => 'test@example.com',
        ]);

        $this->assertNotNull($equipment);
    });

    test('users can create a show equipment listing with all fields', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Complete Show Kit with Accessories',
            'description' => 'Professional show equipment kit including halter, brushes, and grooming supplies. Excellent condition.',
            'condition' => 'Good',
            'location' => 'Orange, NSW',
            'phone_contact' => '0412345678',
            'email_contact' => 'equipment@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/show-equipment', $equipmentData);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Show equipment listing created successfully!');

        $this->assertDatabaseHas('show_equipment_listings', [
            'user_id' => $user->id,
            'title' => 'Complete Show Kit with Accessories',
            'description' => 'Professional show equipment kit including halter, brushes, and grooming supplies. Excellent condition.',
            'condition' => 'Good',
            'location' => 'Orange, NSW',
            'phone_contact' => '0412345678',
            'email_contact' => 'equipment@example.com',
        ]);
    });

    test('creating show equipment listing redirects to dashboard without subscription', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Test Show Equipment',
            'condition' => 'New',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/show-equipment', $equipmentData);

        // Should redirect directly to dashboard (no checkout like steers/studs)
        $response->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Show equipment listing created successfully!');

        // Verify the listing was created (no draft status - should be immediately available)
        $equipment = ShowEquipmentListing::where('user_id', $user->id)
            ->where('title', 'Test Show Equipment')
            ->first();

        $this->assertNotNull($equipment);
    });
});

describe('Show Equipment Listing Dashboard Display', function () {
    test('users can view their own show equipment listings on dashboard', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($equipment->title);
    });

    test('dashboard displays show equipment listing details correctly', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Premium Show Halter',
            'condition' => 'Like New',
            'location' => 'Dubbo, NSW',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Premium Show Halter')
            ->assertSee('Like New')
            ->assertSee('Dubbo, NSW');
    });
});

describe('Show Equipment Listing Editing', function () {
    test('guests cannot edit show equipment listings', function () {
        $equipment = ShowEquipmentListing::factory()->create();

        $this->get("/show-equipment/{$equipment->id}/edit")
            ->assertRedirect('/login');

        $this->put("/show-equipment/{$equipment->id}", [])
            ->assertRedirect('/login');
    });

    test('users can only edit their own show equipment listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get("/show-equipment/{$equipment->id}/edit")
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->put("/show-equipment/{$equipment->id}", [
                'title' => 'Updated Title',
                'condition' => 'Good',
                'location' => 'Test Location',
                'email_contact' => 'test@example.com',
            ])
            ->assertForbidden();
    });

    test('users can view edit form for their own listings', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/show-equipment/{$equipment->id}/edit")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('show-equipment-listings/edit')
                ->has('showEquipment')
            );
    });

    test('users can update their show equipment listings', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'title' => 'Updated Show Equipment Title',
            'description' => 'Updated description for the equipment.',
            'condition' => 'Good',
            'location' => 'Updated Location',
            'phone_contact' => '0423456789',
            'email_contact' => 'updated@example.com',
        ];

        $this->actingAs($user)
            ->put("/show-equipment/{$equipment->id}", $updateData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Show equipment listing updated successfully!');

        $this->assertDatabaseHas('show_equipment_listings', [
            'id' => $equipment->id,
            'title' => 'Updated Show Equipment Title',
            'description' => 'Updated description for the equipment.',
            'condition' => 'Good',
            'location' => 'Updated Location',
            'phone_contact' => '0423456789',
        ]);
    });
});

describe('Show Equipment Listing Deletion', function () {
    test('guests cannot delete show equipment listings', function () {
        $equipment = ShowEquipmentListing::factory()->create();

        $this->delete("/show-equipment/{$equipment->id}")
            ->assertRedirect('/login');
    });

    test('users can only delete their own show equipment listings', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete("/show-equipment/{$equipment->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('show_equipment_listings', ['id' => $equipment->id]);
    });

    test('users can delete their own show equipment listings', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/show-equipment/{$equipment->id}")
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Show equipment listing deleted successfully!');

        $this->assertSoftDeleted('show_equipment_listings', ['id' => $equipment->id]);
    });
});

describe('Show Equipment Listing Photo Uploads', function () {
    test('users can create show equipment listings with photos', function () {
        Storage::fake('public');
        $user = User::factory()->create();

        $photo1 = UploadedFile::fake()->image('equipment1.jpg');
        $photo2 = UploadedFile::fake()->image('equipment2.jpg');

        $equipmentData = [
            'title' => 'Show Equipment with Photos',
            'condition' => 'Like New',
            'location' => 'Brisbane, QLD',
            'email_contact' => 'photos@example.com',
            'photos' => [$photo1, $photo2],
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success');

        $equipment = ShowEquipmentListing::where('user_id', $user->id)->first();
        $this->assertCount(2, $equipment->photos);

        // Verify files were stored
        foreach ($equipment->photos as $photoPath) {
            Storage::disk('public')->assertExists($photoPath);
        }
    });

    test('users can update show equipment listings with additional photos', function () {
        Storage::fake('public');
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'photos' => ['existing-photo.jpg'],
        ]);

        $newPhoto = UploadedFile::fake()->image('new-equipment.jpg');

        $updateData = [
            'title' => $equipment->title,
            'condition' => $equipment->condition,
            'location' => $equipment->location,
            'email_contact' => $equipment->email_contact,
            'phone_contact' => $equipment->phone_contact,
            'photos' => [$newPhoto],
        ];

        $this->actingAs($user)
            ->put("/show-equipment/{$equipment->id}", $updateData)
            ->assertRedirect('/dashboard');

        $equipment->refresh();
        // Should have original photo plus new one
        $this->assertCount(2, $equipment->photos);
        $this->assertContains('existing-photo.jpg', $equipment->photos);
    });

    test('show equipment listings work without photos', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Equipment without Photos',
            'condition' => 'Good',
            'location' => 'Perth, WA',
            'email_contact' => 'nophotos@example.com',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertRedirect('/dashboard');

        $equipment = ShowEquipmentListing::where('user_id', $user->id)->first();
        $this->assertEmpty($equipment->photos);
    });
});

describe('Show Equipment Listing Policy', function () {
    test('any authenticated user can create show equipment listings', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/show-equipment/create')
            ->assertOk();
    });

    test('restore and force delete are not allowed', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

        $policy = new \App\Policies\ShowEquipmentListingPolicy;

        $this->assertFalse($policy->restore($user, $equipment));
        $this->assertFalse($policy->forceDelete($user, $equipment));
    });
});

describe('Show Equipment Listing Validation', function () {
    test('show equipment listing requires either phone or email contact', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Test Equipment',
            'condition' => 'New',
            'location' => 'Test Location',
            // No phone or email contact
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['phone_contact', 'email_contact']);
    });

    test('show equipment listing validates condition enum', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Test Equipment',
            'condition' => 'Invalid Condition',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['condition']);
    });

    test('show equipment listing validates required fields', function () {
        $user = User::factory()->create();

        // Missing required fields
        $equipmentData = [
            'description' => 'Just a description',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['title', 'condition', 'location']);
    });

    test('show equipment listing accepts valid condition values', function () {
        $user = User::factory()->create();
        $validConditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];

        foreach ($validConditions as $condition) {
            $equipmentData = [
                'title' => "Test Equipment - {$condition}",
                'condition' => $condition,
                'location' => 'Test Location',
                'email_contact' => 'test@example.com',
            ];

            $this->actingAs($user)
                ->post('/show-equipment', $equipmentData)
                ->assertRedirect('/dashboard');

            $this->assertDatabaseHas('show_equipment_listings', [
                'title' => "Test Equipment - {$condition}",
                'condition' => $condition,
            ]);
        }
    });

    test('show equipment listing validates title length', function () {
        $user = User::factory()->create();

        // Title too short
        $equipmentData = [
            'title' => 'X',
            'condition' => 'New',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['title']);

        // Title too long
        $equipmentData['title'] = str_repeat('X', 256);
        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['title']);
    });

    test('show equipment listing validates description length', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Test Equipment',
            'description' => str_repeat('X', 1001), // Too long
            'condition' => 'New',
            'location' => 'Test Location',
            'email_contact' => 'test@example.com',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['description']);
    });

    test('show equipment listing validates email format', function () {
        $user = User::factory()->create();

        $equipmentData = [
            'title' => 'Test Equipment',
            'condition' => 'New',
            'location' => 'Test Location',
            'email_contact' => 'invalid-email-format',
        ];

        $this->actingAs($user)
            ->post('/show-equipment', $equipmentData)
            ->assertSessionHasErrors(['email_contact']);
    });
});
