<?php

use App\Models\User;
use App\Models\GeneticsListing;

describe('Genetics Listing Creation', function () {
    it('can view the create genetics listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/genetics/create');

        $page->assertSee('Create')
            ->assertSee('Genetics')
            ->assertSee('Name');
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
            ->click('Log in');

        $page = $this->visit("/genetics/{$genetics->id}/edit");

        $page->assertSee('Edit')
            ->assertSee('Genetics');
    });
});

describe('Genetics Listing on Dashboard', function () {
    it('shows genetics listing on dashboard', function () {
        $user = User::factory()->create();
        GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Dashboard Genetics',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Dashboard Genetics');
    });
});
