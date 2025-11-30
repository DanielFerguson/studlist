<?php

use App\Models\User;
use App\Models\StudListing;

describe('Stud Listing Creation', function () {
    it('can view the create stud listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/studs/create');

        $page->assertSee('Create')
            ->assertSee('Stud')
            ->assertSee('Name');
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
            ->click('Log in');

        $page = $this->visit("/studs/{$stud->id}/edit");

        $page->assertSee('Edit')
            ->assertSee('Stud');
    });
});

describe('Stud Listing on Dashboard', function () {
    it('shows stud listing on dashboard', function () {
        $user = User::factory()->create();
        StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Dashboard Stud',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Dashboard Stud');
    });

    it('shows draft status badge', function () {
        $user = User::factory()->create();
        StudListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Stud',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Draft');
    });
});
