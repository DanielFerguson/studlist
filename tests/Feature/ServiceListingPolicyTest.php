<?php

use App\Models\ServiceListing;
use App\Models\User;
use App\Policies\ServiceListingPolicy;

describe('ServiceListing Policy', function () {
    describe('View Policy', function () {
        it('any user can view any service listing', function () {
            $owner = User::factory()->create();
            $viewer = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

            $policy = new ServiceListingPolicy;

            expect($policy->view($viewer, $listing))->toBeTrue();
            expect($policy->view($owner, $listing))->toBeTrue();
        });
    });

    describe('Create Policy', function () {
        it('authenticated users can create service listings', function () {
            $user = User::factory()->create();
            $policy = new ServiceListingPolicy;

            expect($policy->create($user))->toBeTrue();
        });

        it('guests cannot create service listings', function () {
            $this->get(route('services.create'))
                ->assertRedirect(route('login'));
        });
    });

    describe('Update Policy', function () {
        it('users can update their own service listings', function () {
            $user = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $user->id]);

            $policy = new ServiceListingPolicy;

            expect($policy->update($user, $listing))->toBeTrue();
        });

        it('users cannot update other users service listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

            $policy = new ServiceListingPolicy;

            expect($policy->update($otherUser, $listing))->toBeFalse();
        });
    });

    describe('Delete Policy', function () {
        it('users can delete their own service listings', function () {
            $user = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $user->id]);

            $policy = new ServiceListingPolicy;

            expect($policy->delete($user, $listing))->toBeTrue();
        });

        it('users cannot delete other users service listings', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

            $policy = new ServiceListingPolicy;

            expect($policy->delete($otherUser, $listing))->toBeFalse();
        });
    });

    describe('Ownership-based Policies', function () {
        it('policy correctly identifies ownership across all methods', function () {
            $owner = User::factory()->create();
            $nonOwner = User::factory()->create();
            $listing = ServiceListing::factory()->create(['user_id' => $owner->id]);

            $policy = new ServiceListingPolicy;

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


