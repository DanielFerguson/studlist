<?php

use App\Models\HayListing;
use App\Models\User;

describe('HayListing', function () {
    describe('Create Hay Listing', function () {
        it('authenticated users can view create form', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->get(route('hay.create'))
                ->assertStatus(200)
                ->assertSee('List Hay');
        });

        it('authenticated users can create a hay listing', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->post(route('hay.store'), [
                    'title' => 'Premium Lucerne Hay - 2024',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 500,
                    'storage_type' => 'Shed',
                    'location' => 'Tamworth, NSW',
                    'price_type' => 'Per Bale',
                    'price_per_bale' => 150.00,
                    'phone_contact' => '0412345678',
                ])
                ->assertRedirect(route('dashboard'));

            $this->assertDatabaseHas('hay_listings', [
                'user_id' => $user->id,
                'title' => 'Premium Lucerne Hay - 2024',
                'hay_type' => 'Lucerne',
                'quantity' => 500,
            ]);
        });

        it('requires either phone or email contact', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->post(route('hay.store'), [
                    'title' => 'Test Hay',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 100,
                    'storage_type' => 'Shed',
                    'location' => 'Tamworth, NSW',
                    'price_type' => 'Negotiable',
                ])
                ->assertSessionHasErrors();
        });

        it('requires price_per_bale when price_type is Per Bale', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->post(route('hay.store'), [
                    'title' => 'Test Hay',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 100,
                    'storage_type' => 'Shed',
                    'location' => 'Tamworth, NSW',
                    'price_type' => 'Per Bale',
                    'phone_contact' => '0412345678',
                ])
                ->assertSessionHasErrors('price_per_bale');
        });

        it('requires price_per_tonne when price_type is Per Tonne', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->post(route('hay.store'), [
                    'title' => 'Test Hay',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 100,
                    'storage_type' => 'Shed',
                    'location' => 'Tamworth, NSW',
                    'price_type' => 'Per Tonne',
                    'phone_contact' => '0412345678',
                ])
                ->assertSessionHasErrors('price_per_tonne');
        });

        it('requires delivery_radius_km when delivery_available is true', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->post(route('hay.store'), [
                    'title' => 'Test Hay',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 100,
                    'storage_type' => 'Shed',
                    'location' => 'Tamworth, NSW',
                    'price_type' => 'Negotiable',
                    'phone_contact' => '0412345678',
                    'delivery_available' => true,
                ])
                ->assertSessionHasErrors('delivery_radius_km');
        });
    });

    describe('Edit Hay Listing', function () {
        it('users can view edit form for own listing', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $user->id]);

            $this->actingAs($user)
                ->get(route('hay.edit', $listing))
                ->assertStatus(200)
                ->assertSee('Edit Hay Listing');
        });

        it('users cannot view edit form for other users listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $this->actingAs($otherUser)
                ->get(route('hay.edit', $listing))
                ->assertForbidden();
        });

        it('users can update their own listing', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create([
                'user_id' => $user->id,
                'title' => 'Original Title',
            ]);

            $this->actingAs($user)
                ->put(route('hay.update', $listing), [
                    'title' => 'Updated Title',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 200,
                    'storage_type' => 'Shed',
                    'location' => 'Dubbo, NSW',
                    'price_type' => 'Per Bale',
                    'price_per_bale' => 175.00,
                    'phone_contact' => '0423456789',
                ])
                ->assertRedirect(route('dashboard'));

            $this->assertDatabaseHas('hay_listings', [
                'id' => $listing->id,
                'title' => 'Updated Title',
                'quantity' => 200,
            ]);
        });

        it('users cannot update other users listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $this->actingAs($otherUser)
                ->put(route('hay.update', $listing), [
                    'title' => 'Hacked Title',
                    'hay_type' => 'Lucerne',
                    'bale_type' => 'Round',
                    'quantity' => 999,
                    'storage_type' => 'Shed',
                    'location' => 'Hack, NSW',
                    'price_type' => 'Negotiable',
                    'phone_contact' => '0412345678',
                ])
                ->assertForbidden();
        });
    });

    describe('Delete Hay Listing', function () {
        it('users can delete their own listing', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $user->id]);

            $this->actingAs($user)
                ->delete(route('hay.destroy', $listing))
                ->assertRedirect(route('dashboard'));

            $this->assertSoftDeleted('hay_listings', [
                'id' => $listing->id,
            ]);
        });

        it('users cannot delete other users listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $this->actingAs($otherUser)
                ->delete(route('hay.destroy', $listing))
                ->assertForbidden();

            $this->assertDatabaseHas('hay_listings', [
                'id' => $listing->id,
                'deleted_at' => null,
            ]);
        });
    });

    describe('Public Hay Listings', function () {
        it('public can view hay listings index', function () {
            HayListing::factory()->count(5)->create();

            $this->get(route('hay.index'))
                ->assertStatus(200)
                ->assertSee('Hay Listings')
                ->assertSee('listings found');
        });

        it('public can view single hay listing', function () {
            $listing = HayListing::factory()->create([
                'title' => 'Test Hay Listing',
            ]);

            $this->get(route('hay.show', $listing))
                ->assertStatus(200)
                ->assertSee('Test Hay Listing');
        });

        it('hay listings appear in search results', function () {
            $listing = HayListing::factory()->create([
                'title' => 'Searchable Hay Listing',
            ]);

            $this->get(route('search', ['category' => ['hay']]))
                ->assertStatus(200)
                ->assertSee('Searchable Hay Listing');
        });
    });

    describe('Hay Listing Model', function () {
        it('generates correct display_price for per bale', function () {
            $listing = HayListing::factory()->create([
                'price_type' => 'Per Bale',
                'price_per_bale' => 150.00,
            ]);

            expect($listing->display_price)->toBe('$150.00/bale');
        });

        it('generates correct display_price for per tonne', function () {
            $listing = HayListing::factory()->create([
                'price_type' => 'Per Tonne',
                'price_per_tonne' => 350.00,
                'price_per_bale' => null,
            ]);

            expect($listing->display_price)->toBe('$350.00/tonne');
        });

        it('generates correct display_price for negotiable', function () {
            $listing = HayListing::factory()->create([
                'price_type' => 'Negotiable',
                'price_per_bale' => null,
                'price_per_tonne' => null,
            ]);

            expect($listing->display_price)->toBe('Negotiable');
        });

        it('withCoordinates scope returns only listings with coordinates', function () {
            HayListing::factory()->create([
                'latitude' => -31.2532,
                'longitude' => 150.9267,
            ]);
            HayListing::factory()->create([
                'latitude' => null,
                'longitude' => null,
            ]);

            $withCoords = HayListing::withCoordinates()->get();

            expect($withCoords)->toHaveCount(1);
        });
    });

    describe('Dashboard Hay Listings', function () {
        it('dashboard shows users hay listings', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create([
                'user_id' => $user->id,
                'title' => 'My Hay Listing',
            ]);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertStatus(200)
                ->assertSee('My Hay Listing');
        });

        it('dashboard does not show other users hay listings', function () {
            $user = User::factory()->create();
            $otherUser = User::factory()->create();
            $otherListing = HayListing::factory()->create([
                'user_id' => $otherUser->id,
                'title' => 'Other Users Hay',
            ]);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertStatus(200)
                ->assertDontSee('Other Users Hay');
        });
    });
});
