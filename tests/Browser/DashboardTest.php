<?php

use App\Models\User;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
use App\Models\ServiceListing;

describe('Dashboard Access', function () {
    it('requires authentication to access dashboard', function () {
        $page = $this->visit('/dashboard');

        $page->assertPathIs('/login');
    });

    it('authenticated users can access dashboard', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertPathIs('/dashboard')
            ->assertSee('Dashboard');
    });
});

describe('Dashboard Listing Overview', function () {
    it('shows steer listings section on dashboard', function () {
        $user = User::factory()->create();
        SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Steer on Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Steers')
            ->assertSee('Test Steer on Dashboard');
    });

    it('shows stud listings section on dashboard', function () {
        $user = User::factory()->create();
        StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Stud on Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Studs')
            ->assertSee('Test Stud on Dashboard');
    });

    it('shows genetics listings section on dashboard', function () {
        $user = User::factory()->create();
        GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Genetics on Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Genetics')
            ->assertSee('Test Genetics on Dashboard');
    });

    it('shows equipment listings section on dashboard', function () {
        $user = User::factory()->create();
        ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Equipment on Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Equipment')
            ->assertSee('Test Equipment on Dashboard');
    });

    it('shows service listings section on dashboard', function () {
        $user = User::factory()->create();
        ServiceListing::factory()->create([
            'user_id' => $user->id,
            'business_name' => 'Test Service on Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Services')
            ->assertSee('Test Service on Dashboard');
    });
});

describe('Dashboard Empty States', function () {
    it('shows empty state when no listings exist', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        // Should see dashboard but no listings
        $page->assertSee('Dashboard');
    });
});

describe('Dashboard Action Buttons', function () {
    it('shows edit button for owned listings', function () {
        $user = User::factory()->create();
        SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Editable From Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Edit');
    });

    it('shows delete button for owned listings', function () {
        $user = User::factory()->create();
        SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Deletable From Dashboard',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Delete');
    });

    it('can navigate to edit page from dashboard', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Steer To Edit',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->click('Edit');

        $page->assertPathIs("/steers/{$steer->id}/edit");
    });
});

describe('Dashboard Status Badges', function () {
    it('displays draft status badge correctly', function () {
        $user = User::factory()->create();
        SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Status Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Draft');
    });

    it('displays active status badge correctly', function () {
        $user = User::factory()->create();
        SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'name' => 'Active Status Steer',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Active');
    });
});

describe('Dashboard Multiple Listings', function () {
    it('displays multiple listings of different types', function () {
        $user = User::factory()->create();

        SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Multi Test Steer',
        ]);

        StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Multi Test Stud',
        ]);

        GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Multi Test Genetics',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertSee('Multi Test Steer')
            ->assertSee('Multi Test Stud')
            ->assertSee('Multi Test Genetics');
    });
});
