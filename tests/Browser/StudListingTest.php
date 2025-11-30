<?php

use App\Models\User;
use App\Models\StudListing;

describe('Stud Listing Creation', function () {
    it('can view the create stud listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/studs/create');

        $page->assertSee('Create Stud Listing')
            ->assertSee('Name')
            ->assertSee('Date of Birth')
            ->assertSee('Breed')
            ->assertSee('Colour')
            ->assertSee('Tattoo Number');
    });

    it('can create a stud listing with required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/studs/create');

        $page->fill('name', 'Champion Bull')
            ->fill('dob', '2020-03-15')
            ->fill('breed', 'Charolais')
            ->fill('colour', 'White')
            ->fill('tattoo_number', 'CH456')
            ->fill('location', 'Brisbane, QLD')
            ->fill('email_contact', 'breeder@example.com')
            ->click('Create Listing')
            ;

        $this->assertDatabaseHas('stud_listings', [
            'user_id' => $user->id,
            'name' => 'Champion Bull',
            'breed' => 'Charolais',
            'tattoo_number' => 'CH456',
        ]);
    });

    it('can create a stud listing with optional fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/studs/create');

        $page->fill('name', 'Elite Sire')
            ->fill('dob', '2019-01-10')
            ->fill('breed', 'Angus')
            ->fill('colour', 'Black')
            ->fill('location', 'Melbourne, VIC')
            ->fill('email_contact', 'elite@genetics.com')
            ->fill('sire', 'Grand Champion')
            ->fill('dam', 'Elite Cow')
            ->fill('registration_link', 'https://angus.org.au/registration')
            ->fill('description', 'Exceptional genetics with proven progeny.')
            ->click('Create Listing')
            ;

        $this->assertDatabaseHas('stud_listings', [
            'user_id' => $user->id,
            'name' => 'Elite Sire',
            'sire' => 'Grand Champion',
            'dam' => 'Elite Cow',
        ]);
    });
});

describe('Stud Listing Editing', function () {
    it('can view the edit form for own stud listing', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Editable Stud',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/studs/{$stud->id}/edit");

        $page->assertSee('Edit Stud Listing')
            ->assertSee('Editable Stud');
    });

    it('can update a stud listing', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Stud',
            'tattoo_number' => 'OLD123',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/studs/{$stud->id}/edit");

        $page->fill('name', 'Updated Stud')
            ->fill('tattoo_number', 'NEW456')
            ->click('Update Listing')
            ->waitForNavigation()
            ->assertUrlIs('/dashboard');

        $this->assertDatabaseHas('stud_listings', [
            'id' => $stud->id,
            'name' => 'Updated Stud',
            'tattoo_number' => 'NEW456',
        ]);
    });
});

describe('Stud Listing Deletion', function () {
    it('can delete own stud listing from dashboard', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Stud To Delete',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Stud To Delete');

        // Click delete and confirm
        $page->click('Delete')
            ->click('Confirm')
            ;

        $this->assertSoftDeleted('stud_listings', ['id' => $stud->id]);
    });
});

describe('Stud Listing Status Display', function () {
    it('shows draft status badge on dashboard', function () {
        $user = User::factory()->create();
        StudListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Stud',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Draft Stud')
            ->assertSee('Draft');
    });

    it('shows active status badge on dashboard', function () {
        $user = User::factory()->create();
        StudListing::factory()->active()->create([
            'user_id' => $user->id,
            'name' => 'Active Stud',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Active Stud')
            ->assertSee('Active');
    });
});
