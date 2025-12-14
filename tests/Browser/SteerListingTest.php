<?php

use App\Models\SteerListing;
use App\Models\User;

describe('Steer Listing Creation', function () {
    it('can view the create steer listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/steers/create');

        $page->assertSee('Create')
            ->assertSee('Steer')
            ->assertSee('Name');
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
            ->click('Log in');

        $page = $this->visit("/steers/{$steer->id}/edit");

        $page->assertSee('Edit')
            ->assertSee('Steer');
    });
});

describe('Steer Listing on Dashboard', function () {
    it('shows steer listing on dashboard', function () {
        $user = User::factory()->create();
        SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Dashboard Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Dashboard Steer');
    });

    it('shows draft status badge', function () {
        $user = User::factory()->create();
        SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Draft');
    });
});
