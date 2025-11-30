<?php

use App\Models\User;
use App\Models\SteerListing;

describe('Steer Listing Creation', function () {
    it('can view the create steer listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/steers/create');

        $page->assertSee('Create Steer Listing')
            ->assertSee('Name')
            ->assertSee('Date of Birth')
            ->assertSee('Breed')
            ->assertSee('Colour')
            ->assertSee('Location');
    });

    it('can create a steer listing with required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/steers/create');

        $page->fill('name', 'My Test Steer')
            ->fill('dob', '2023-06-15')
            ->fill('breed', 'Angus')
            ->fill('colour', 'Black')
            ->fill('location', 'Sydney, NSW')
            ->fill('email_contact', 'contact@example.com')
            ->click('Create Listing')
            ;

        $this->assertDatabaseHas('steer_listings', [
            'user_id' => $user->id,
            'name' => 'My Test Steer',
            'breed' => 'Angus',
            'colour' => 'Black',
        ]);
    });

    it('shows validation errors for missing required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/steers/create');

        // Submit without filling required fields
        $page->click('Create Listing')
            ->assertSee('name')
            ->assertSee('breed')
            ->assertSee('location');
    });
});

describe('Steer Listing Editing', function () {
    it('can view the edit form for own steer listing', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Editable Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/steers/{$steer->id}/edit");

        $page->assertSee('Edit Steer Listing')
            ->assertSee('Editable Steer');
    });

    it('can update a steer listing', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Steer Name',
            'breed' => 'Angus',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/steers/{$steer->id}/edit");

        $page->fill('name', 'Updated Steer Name')
            ->fill('breed', 'Hereford')
            ->click('Update Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('steer_listings', [
            'id' => $steer->id,
            'name' => 'Updated Steer Name',
            'breed' => 'Hereford',
        ]);
    });
});

describe('Steer Listing Deletion', function () {
    it('can delete own steer listing from dashboard', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Steer To Delete',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Steer To Delete');

        // Click delete button and confirm in modal
        $page->click('Delete')
            ->click('Confirm')
            ;

        $this->assertSoftDeleted('steer_listings', ['id' => $steer->id]);
    });
});

describe('Steer Listing Status Display', function () {
    it('shows draft status badge on dashboard', function () {
        $user = User::factory()->create();
        SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Draft Steer')
            ->assertSee('Draft');
    });

    it('shows active status badge on dashboard', function () {
        $user = User::factory()->create();
        SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'name' => 'Active Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Active Steer')
            ->assertSee('Active');
    });
});
