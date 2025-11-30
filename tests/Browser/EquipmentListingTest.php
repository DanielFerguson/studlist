<?php

use App\Models\User;
use App\Models\ShowEquipmentListing;

describe('Show Equipment Listing Creation', function () {
    it('can view the create equipment listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/show-equipment/create');

        $page->assertSee('Create Show Equipment Listing')
            ->assertSee('Title')
            ->assertSee('Condition')
            ->assertSee('Location')
            ->assertSee('Description');
    });

    it('can create an equipment listing with required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/show-equipment/create');

        $page->fill('title', 'Professional Show Halter')
            ->select('condition', 'Like New')
            ->fill('location', 'Sydney, NSW')
            ->fill('email_contact', 'equipment@example.com')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('show_equipment_listings', [
            'user_id' => $user->id,
            'title' => 'Professional Show Halter',
            'condition' => 'Like New',
            'location' => 'Sydney, NSW',
        ]);
    });

    it('can create an equipment listing with description', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/show-equipment/create');

        $page->fill('title', 'Complete Grooming Kit')
            ->select('condition', 'Good')
            ->fill('location', 'Brisbane, QLD')
            ->fill('email_contact', 'grooming@example.com')
            ->fill('description', 'Includes brushes, combs, and show supplies. Great for beginners.')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('show_equipment_listings', [
            'user_id' => $user->id,
            'title' => 'Complete Grooming Kit',
            'description' => 'Includes brushes, combs, and show supplies. Great for beginners.',
        ]);
    });
});

describe('Show Equipment Listing Editing', function () {
    it('can view the edit form for own equipment listing', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Editable Equipment',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/show-equipment/{$equipment->id}/edit");

        $page->assertSee('Edit Show Equipment Listing')
            ->assertSee('Editable Equipment');
    });

    it('can update an equipment listing', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Equipment',
            'condition' => 'New',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/show-equipment/{$equipment->id}/edit");

        $page->fill('title', 'Updated Equipment')
            ->select('condition', 'Good')
            ->click('Update Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('show_equipment_listings', [
            'id' => $equipment->id,
            'title' => 'Updated Equipment',
            'condition' => 'Good',
        ]);
    });
});

describe('Show Equipment Listing Deletion', function () {
    it('can delete own equipment listing from dashboard', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Equipment To Delete',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Equipment To Delete');

        // Click delete and confirm
        $page->click('Delete')
            ->click('Confirm')
            ;

        $this->assertSoftDeleted('show_equipment_listings', ['id' => $equipment->id]);
    });
});

describe('Show Equipment Listing Dashboard Display', function () {
    it('shows equipment listing details on dashboard', function () {
        $user = User::factory()->create();
        ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Dashboard Equipment',
            'condition' => 'Like New',
            'location' => 'Perth, WA',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Dashboard Equipment')
            ->assertSee('Like New')
            ->assertSee('Perth, WA');
    });
});

describe('Show Equipment Condition Values', function () {
    it('can select all valid condition values', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/show-equipment/create');

        // Verify condition dropdown options are available
        $page->assertSee('New')
            ->assertSee('Like New')
            ->assertSee('Good')
            ->assertSee('Fair')
            ->assertSee('Poor');
    });
});
