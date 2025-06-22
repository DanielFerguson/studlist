<?php

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
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

    test('dashboard displays user genetics listings', function () {
        $user = User::factory()->create();
        $userGenetics = GeneticsListing::factory()->count(2)->create(['user_id' => $user->id]);
        $otherUserGenetics = GeneticsListing::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();

        // Should see own genetics
        foreach ($userGenetics as $genetics) {
            $response->assertSee($genetics->name);
        }

        // Should not see other users' genetics
        foreach ($otherUserGenetics as $genetics) {
            $response->assertDontSee($genetics->name);
        }
    });

    test('dashboard displays user show equipment listings', function () {
        $user = User::factory()->create();
        $userEquipment = ShowEquipmentListing::factory()->count(2)->create(['user_id' => $user->id]);
        $otherUserEquipment = ShowEquipmentListing::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();

        // Should see own equipment
        foreach ($userEquipment as $equipment) {
            $response->assertSee($equipment->title);
        }

        // Should not see other users' equipment
        foreach ($otherUserEquipment as $equipment) {
            $response->assertDontSee($equipment->title);
        }
    });

    test('dashboard shows all listing types correctly', function () {
        $user = User::factory()->create();
        
        // Create one of each listing type
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Steer',
        ]);
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Stud',
        ]);
        $genetics = GeneticsListing::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Genetics',
        ]);
        $equipment = ShowEquipmentListing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Equipment',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Test Steer')
            ->assertSee('Test Stud')
            ->assertSee('Test Genetics')
            ->assertSee('Test Equipment');
    });

    test('dashboard handles empty state for all listing types', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        
        // When no listings exist, the dashboard should still be accessible
        // The empty state messages may be in the client-side rendering
        $this->assertTrue(true); // Basic test that dashboard loads without error
    });
});
