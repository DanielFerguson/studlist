<?php

use App\Models\HayListing;
use App\Models\User;
use App\Policies\HayListingPolicy;

describe('HayListing Policy', function () {
    describe('View Policy', function () {
        it('any user can view any hay listing', function () {
            $owner = User::factory()->create();
            $viewer = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $policy = new HayListingPolicy;

            expect($policy->view($viewer, $listing))->toBeTrue();
            expect($policy->view($owner, $listing))->toBeTrue();
        });
    });

    describe('Create Policy', function () {
        it('authenticated users can create hay listings', function () {
            $user = User::factory()->create();
            $policy = new HayListingPolicy;

            expect($policy->create($user))->toBeTrue();
        });

        it('guests cannot create hay listings', function () {
            $this->get(route('hay.create'))
                ->assertRedirect(route('login'));
        });
    });

    describe('Update Policy', function () {
        it('users can update their own hay listings', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $user->id]);

            $policy = new HayListingPolicy;

            expect($policy->update($user, $listing))->toBeTrue();
        });

        it('users cannot update other users hay listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $policy = new HayListingPolicy;

            expect($policy->update($otherUser, $listing))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        it('users can delete their own hay listings', function () {
            $user = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $user->id]);

            $policy = new HayListingPolicy;

            expect($policy->delete($user, $listing))->toBeTrue();
        });

        it('users cannot delete other users hay listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $policy = new HayListingPolicy;

            expect($policy->delete($otherUser, $listing))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        it('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $nonOwner = User::factory()->create();
            $listing = HayListing::factory()->create(['user_id' => $owner->id]);

            $policy = new HayListingPolicy;

            // Owner can do everything
            expect($policy->view($owner, $listing))->toBeTrue();
            expect($policy->update($owner, $listing))->toBeTrue();
            expect($policy->delete($owner, $listing))->toBeTrue();

            // Non-owner can only view
            expect($policy->view($nonOwner, $listing))->toBeTrue();
            expect($policy->update($nonOwner, $listing))->toBeFalse();
            expect($policy->delete($nonOwner, $listing))->toBeFalse();
        });
    });
});
