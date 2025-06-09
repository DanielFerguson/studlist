<?php

use App\Models\SteerListing;
use App\Models\User;

describe('Dashboard Access', function () {
    test('guests are redirected to the login page', function () {
        $this->get('/dashboard')->assertRedirect('/login');
    });

    test('authenticated users can visit the dashboard', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('app');
    });
});

describe('Dashboard Content', function () {
    test('dashboard displays user steer listings', function () {
        $user = User::factory()->create();
        $userSteers = SteerListing::factory()->count(3)->create(['user_id' => $user->id]);
        $otherUserSteers = SteerListing::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();

        // Should see own steers
        foreach ($userSteers as $steer) {
            $response->assertSee($steer->name);
        }

        // Should not see other users' steers
        foreach ($otherUserSteers as $steer) {
            $response->assertDontSee($steer->name);
        }
    });

    test('dashboard shows steer listing statuses correctly', function () {
        $user = User::factory()->create();
        $draftSteer = SteerListing::factory()->draft()->create([
            'user_id' => $user->id,
            'name' => 'Draft Steer',
        ]);
        $activeSteer = SteerListing::factory()->active()->create([
            'user_id' => $user->id,
            'name' => 'Active Steer',
        ]);
        $cancelledSteer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Cancelled Steer',
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Draft Steer')
            ->assertSee('Active Steer')
            ->assertSee('Cancelled Steer')
            ->assertSee('Draft')
            ->assertSee('Active')
            ->assertSee('Cancelled');
    });

    test('dashboard handles empty state gracefully', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    });
});
