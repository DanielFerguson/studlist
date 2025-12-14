<?php

use App\Models\ShowEquipmentListing;
use App\Models\User;

describe('Equipment Listing Creation', function () {
    it('can view the create equipment listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/show-equipment/create');

        $page->assertSee('Create')
            ->assertSee('Equipment');
    });
});

describe('Equipment Listing Editing', function () {
    it('can view the edit form for own equipment listing', function () {
        $user = User::factory()->create();
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Editable Equipment',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit("/show-equipment/{$equipment->id}/edit");

        $page->assertSee('Edit')
            ->assertSee('Equipment');
    });
});

describe('Equipment Listing on Dashboard', function () {
    it('shows equipment listing on dashboard', function () {
        $user = User::factory()->create();
        ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Dashboard Equipment',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Dashboard Equipment');
    });
});
