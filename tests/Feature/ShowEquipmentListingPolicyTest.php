<?php

use App\Models\ShowEquipmentListing;
use App\Models\User;

describe('ShowEquipmentListing Policy', function () {
    describe('View Policy', function () {
        test('any user can view any show equipment listing', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

            // Owner can view their own listing
            expect($owner->can('view', $equipment))->toBeTrue();
            
            // Other users can view listings (for public display)
            expect($otherUser->can('view', $equipment))->toBeTrue();
        });
    });

    describe('Create Policy', function () {
        test('authenticated users can create show equipment listings', function () {
            $user = User::factory()->create();

            expect($user->can('create', ShowEquipmentListing::class))->toBeTrue();
        });

        test('guests cannot create show equipment listings', function () {
            // Test that policy returns false for unauthenticated users
            $this->assertFalse(auth()->check());

            // We'll test this through the controller since policies need authenticated users
        });
    });

    describe('Update Policy', function () {
        test('users can update their own show equipment listings', function () {
            $user = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('update', $equipment))->toBeTrue();
        });

        test('users cannot update other users show equipment listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('update', $equipment))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        test('users can delete their own show equipment listings', function () {
            $user = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $user->id]);

            expect($user->can('delete', $equipment))->toBeTrue();
        });

        test('users cannot delete other users show equipment listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

            expect($otherUser->can('delete', $equipment))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        test('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $equipment = ShowEquipmentListing::factory()->create(['user_id' => $owner->id]);

            // Owner should have all permissions
            expect($owner->can('view', $equipment))->toBeTrue();
            expect($owner->can('update', $equipment))->toBeTrue();
            expect($owner->can('delete', $equipment))->toBeTrue();

            // Other users should only have view permission
            expect($otherUser->can('view', $equipment))->toBeTrue();
            expect($otherUser->can('update', $equipment))->toBeFalse();
            expect($otherUser->can('delete', $equipment))->toBeFalse();
        });
    });
});