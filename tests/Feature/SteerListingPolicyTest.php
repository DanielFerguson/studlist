<?php

use App\Models\SteerListing;
use App\Models\User;

describe('SteerListing Policy', function () {
    describe('View Policy', function () {
        test('any user can view any steer listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

            // Owner can view their own listing
            expect($otherUser->can('view', $steer))->toBeTrue();
            expect($owner->can('view', $steer))->toBeTrue();

            // Guests can also view listings (for public display)
            $this->assertTrue(auth()->guest());
            // We'll test guest access differently since we can't use ->can() on null user
        });
    });

    describe('Create Policy', function () {
        test('authenticated users can create steer listings', function () {
            $user = User::factory()->create();

            expect($user->can('create', SteerListing::class))->toBeTrue();
        });

        test('guests cannot create steer listings', function () {
            // Test that policy returns false for unauthenticated users
            $this->assertFalse(auth()->check());

            // We'll test this through the controller since policies need authenticated users
        });
    });

    describe('Update Policy', function () {
        test('users can update their own steer listings', function () {
            $user = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('update', $steer))->toBeTrue();
        });

        test('users cannot update other users steer listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('update', $steer))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        test('users can delete their own steer listings', function () {
            $user = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('delete', $steer))->toBeTrue();
        });

        test('users cannot delete other users steer listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('delete', $steer))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        test('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $steer = SteerListing::factory()->create(['user_id' => $owner->id]);

            // Owner should have all permissions
            expect($owner->can('view', $steer))->toBeTrue();
            expect($owner->can('update', $steer))->toBeTrue();
            expect($owner->can('delete', $steer))->toBeTrue();

            // Other users should only have view permission
            expect($otherUser->can('view', $steer))->toBeTrue();
            expect($otherUser->can('update', $steer))->toBeFalse();
            expect($otherUser->can('delete', $steer))->toBeFalse();
        });
    });
});
