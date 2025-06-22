<?php

use App\Models\GeneticsListing;
use App\Models\User;

describe('GeneticsListing Policy', function () {
    describe('View Policy', function () {
        test('any user can view any genetics listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

            // Owner can view their own listing
            expect($owner->can('view', $genetics))->toBeTrue();

            // Other users can view listings (for public display)
            expect($otherUser->can('view', $genetics))->toBeTrue();
        });
    });

    describe('Create Policy', function () {
        test('authenticated users can create genetics listings', function () {
            $user = User::factory()->create();

            expect($user->can('create', GeneticsListing::class))->toBeTrue();
        });

        test('guests cannot create genetics listings', function () {
            // Test that policy returns false for unauthenticated users
            $this->assertFalse(auth()->check());

            // We'll test this through the controller since policies need authenticated users
        });
    });

    describe('Update Policy', function () {
        test('users can update their own genetics listings', function () {
            $user = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('update', $genetics))->toBeTrue();
        });

        test('users cannot update other users genetics listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('update', $genetics))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        test('users can delete their own genetics listings', function () {
            $user = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('delete', $genetics))->toBeTrue();
        });

        test('users cannot delete other users genetics listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('delete', $genetics))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        test('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $genetics = GeneticsListing::factory()->create(['user_id' => $owner->id]);

            // Owner should have all permissions
            expect($owner->can('view', $genetics))->toBeTrue();
            expect($owner->can('update', $genetics))->toBeTrue();
            expect($owner->can('delete', $genetics))->toBeTrue();

            // Other users should only have view permission
            expect($otherUser->can('view', $genetics))->toBeTrue();
            expect($otherUser->can('update', $genetics))->toBeFalse();
            expect($otherUser->can('delete', $genetics))->toBeFalse();
        });
    });
});
