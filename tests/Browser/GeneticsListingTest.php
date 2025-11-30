<?php

use App\Models\User;
use App\Models\GeneticsListing;

describe('Genetics Listing Creation', function () {
    it('can view the create genetics listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/genetics/create');

        $page->assertSee('Create Genetics Listing')
            ->assertSee('Name')
            ->assertSee('Price')
            ->assertSee('Breed')
            ->assertSee('Type')
            ->assertSee('Storage Location');
    });

    it('can create a genetics listing with required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/genetics/create');

        $page->fill('name', 'Premium Angus Semen')
            ->fill('price', '450')
            ->fill('breed', 'Angus')
            ->select('type', 'Semen Straws')
            ->select('storage_location', 'NSW')
            ->fill('email_contact', 'genetics@example.com')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertUrlIs('/dashboard');

        $this->assertDatabaseHas('genetics_listings', [
            'user_id' => $user->id,
            'name' => 'Premium Angus Semen',
            'price' => 450,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
        ]);
    });

    it('can create a genetics listing with embryos type', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/genetics/create');

        $page->fill('name', 'Elite Charolais Embryos')
            ->fill('price', '1500')
            ->fill('breed', 'Charolais')
            ->select('type', 'Embryos')
            ->select('storage_location', 'QLD')
            ->fill('email_contact', 'embryos@example.com')
            ->fill('sire', 'Champion Bull')
            ->fill('dam', 'Elite Cow')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertUrlIs('/dashboard');

        $this->assertDatabaseHas('genetics_listings', [
            'user_id' => $user->id,
            'name' => 'Elite Charolais Embryos',
            'type' => 'Embryos',
            'sire' => 'Champion Bull',
        ]);
    });
});

describe('Genetics Listing Editing', function () {
    it('can view the edit form for own genetics listing', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Editable Genetics',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/genetics/{$genetics->id}/edit");

        $page->assertSee('Edit Genetics Listing')
            ->assertSee('Editable Genetics');
    });

    it('can update a genetics listing', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Genetics',
            'price' => 500,
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/genetics/{$genetics->id}/edit");

        $page->fill('name', 'Updated Genetics')
            ->fill('price', '750')
            ->click('Update Listing')
            ->waitForNavigation()
            ->assertUrlIs('/dashboard');

        $this->assertDatabaseHas('genetics_listings', [
            'id' => $genetics->id,
            'name' => 'Updated Genetics',
            'price' => 750,
        ]);
    });
});

describe('Genetics Listing Deletion', function () {
    it('can delete own genetics listing from dashboard', function () {
        $user = User::factory()->create();
        $genetics = GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Genetics To Delete',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Genetics To Delete');

        // Click delete and confirm
        $page->click('Delete')
            ->click('Confirm')
            ;

        $this->assertSoftDeleted('genetics_listings', ['id' => $genetics->id]);
    });
});

describe('Genetics Listing Dashboard Display', function () {
    it('shows genetics listing details on dashboard', function () {
        $user = User::factory()->create();
        GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Dashboard Genetics',
            'type' => 'Semen Straws',
            'price' => 600,
            'storage_location' => 'VIC',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Dashboard Genetics')
            ->assertSee('Semen Straws')
            ->assertSee('600')
            ->assertSee('VIC');
    });
});
