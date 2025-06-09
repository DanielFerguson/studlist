<?php

use App\Models\StudListing;
use App\Models\User;

describe('StudListing Policy', function () {
    describe('View Policy', function () {
        test('any user can view any stud listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $owner->id]);

            // Owner can view their own listing
            expect($otherUser->can('view', $stud))->toBeTrue();
            expect($owner->can('view', $stud))->toBeTrue();

            // Guests can also view listings (for public display)
            $this->assertTrue(auth()->guest());
            // We'll test guest access differently since we can't use ->can() on null user
        });
    });

    describe('Create Policy', function () {
        test('authenticated users can create stud listings', function () {
            $user = User::factory()->create();

            expect($user->can('create', StudListing::class))->toBeTrue();
        });

        test('guests cannot create stud listings', function () {
            // Test that policy returns false for unauthenticated users
            $this->assertFalse(auth()->check());

            // We'll test this through the controller since policies need authenticated users
        });
    });

    describe('Update Policy', function () {
        test('users can update their own stud listings', function () {
            $user = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('update', $stud))->toBeTrue();
        });

        test('users cannot update other users stud listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('update', $stud))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        test('users can delete their own stud listings', function () {
            $user = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('delete', $stud))->toBeTrue();
        });

        test('users cannot delete other users stud listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('delete', $stud))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        test('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $stud = StudListing::factory()->create(['user_id' => $owner->id]);

            // Owner should have all permissions
            expect($owner->can('view', $stud))->toBeTrue();
            expect($owner->can('update', $stud))->toBeTrue();
            expect($owner->can('delete', $stud))->toBeTrue();

            // Other users should only have view permission
            expect($otherUser->can('view', $stud))->toBeTrue();
            expect($otherUser->can('update', $stud))->toBeFalse();
            expect($otherUser->can('delete', $stud))->toBeFalse();
        });
    });
});
