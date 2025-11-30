<?php

use App\Models\User;
use App\Models\ServiceListing;

describe('Service Listing Creation', function () {
    it('can view the create service listing form', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/services/create');

        $page->assertSee('Create Service Listing')
            ->assertSee('Title')
            ->assertSee('Service Type')
            ->assertSee('Location')
            ->assertSee('Description');
    });

    it('can create a service listing with required fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/services/create');

        $page->fill('title', 'Professional Cattle Clipping')
            ->select('service_type', 'Clipping')
            ->fill('location', 'Toowoomba, QLD')
            ->fill('email_contact', 'clipping@example.com')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('service_listings', [
            'user_id' => $user->id,
            'title' => 'Professional Cattle Clipping',
            'service_type' => 'Clipping',
            'location' => 'Toowoomba, QLD',
        ]);
    });

    it('can create a service listing with all fields', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/services/create');

        $page->fill('title', 'Expert Show Training')
            ->select('service_type', 'Training')
            ->fill('location', 'Rockhampton, QLD')
            ->fill('email_contact', 'training@example.com')
            ->fill('phone_contact', '0412345678')
            ->fill('description', 'Professional show training with 20+ years experience. All breeds welcome.')
            ->click('Create Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('service_listings', [
            'user_id' => $user->id,
            'title' => 'Expert Show Training',
            'service_type' => 'Training',
            'description' => 'Professional show training with 20+ years experience. All breeds welcome.',
        ]);
    });
});

describe('Service Listing Editing', function () {
    it('can view the edit form for own service listing', function () {
        $user = User::factory()->create();
        $service = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Editable Service',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/services/{$service->id}/edit");

        $page->assertSee('Edit Service Listing')
            ->assertSee('Editable Service');
    });

    it('can update a service listing', function () {
        $user = User::factory()->create();
        $service = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Service',
            'service_type' => 'Clipping',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = visit("/services/{$service->id}/edit");

        $page->fill('title', 'Updated Service')
            ->select('service_type', 'Training')
            ->click('Update Listing')
            ->waitForNavigation()
            ->assertPathIs('/dashboard');

        $this->assertDatabaseHas('service_listings', [
            'id' => $service->id,
            'title' => 'Updated Service',
            'service_type' => 'Training',
        ]);
    });
});

describe('Service Listing Deletion', function () {
    it('can delete own service listing from dashboard', function () {
        $user = User::factory()->create();
        $service = ServiceListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Service To Delete',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Service To Delete');

        // Click delete and confirm
        $page->click('Delete')
            ->click('Confirm')
            ;

        $this->assertSoftDeleted('service_listings', ['id' => $service->id]);
    });
});

describe('Service Listing Dashboard Display', function () {
    it('shows service listing details on dashboard', function () {
        $user = User::factory()->create();
        ServiceListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Dashboard Service',
            'service_type' => 'Clipping',
            'location' => 'Adelaide, SA',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/dashboard');

        $page->assertSee('Dashboard Service')
            ->assertSee('Clipping')
            ->assertSee('Adelaide, SA');
    });
});

describe('Service Type Values', function () {
    it('can select all valid service types', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/services/create');

        // Verify service type dropdown options are available
        $page->assertSee('Clipping')
            ->assertSee('Training')
            ->assertSee('Transport')
            ->assertSee('Other');
    });
});
