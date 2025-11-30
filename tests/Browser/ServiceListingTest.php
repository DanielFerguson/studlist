<?php

use App\Models\User;
use App\Models\ServiceListing;

describe('Service Listing Creation', function () {
    it('can view the create service listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/services/create');

        $page->assertSee('Create')
            ->assertSee('Service')
            ->assertSee('Business');
    });
});

describe('Service Listing Editing', function () {
    it('can view the edit form for own service listing', function () {
        $user = User::factory()->create();
        $service = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'Editable Service',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit("/services/{$service->id}/edit");

        $page->assertSee('Edit')
            ->assertSee('Service');
    });
});

describe('Service Listing on Dashboard', function () {
    it('shows service listing on dashboard', function () {
        $user = User::factory()->create();
        ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'Dashboard Service',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Dashboard Service');
    });
});
