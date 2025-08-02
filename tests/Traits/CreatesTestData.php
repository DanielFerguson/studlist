<?php

namespace Tests\Traits;

use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

trait CreatesTestData
{
    /**
     * Create a user with a Stripe customer ID
     */
    protected function createUserWithStripeId(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'stripe_id' => 'cus_test_' . uniqid(),
        ], $attributes));
    }

    /**
     * Create a user with an active subscription
     */
    protected function createUserWithSubscription(array $userAttributes = [], array $subscriptionAttributes = []): User
    {
        $user = $this->createUserWithStripeId($userAttributes);
        
        $subscription = Subscription::create(array_merge([
            'user_id' => $user->id,
            'type' => 'default',
            'stripe_id' => 'sub_test_' . uniqid(),
            'stripe_status' => 'active',
            'stripe_price' => config('cashier.price_ids.steer_listing', 'price_test'),
            'quantity' => 1,
        ], $subscriptionAttributes));

        // Create subscription item
        SubscriptionItem::create([
            'subscription_id' => $subscription->id,
            'stripe_id' => 'si_test_' . uniqid(),
            'stripe_product' => 'prod_test',
            'stripe_price' => $subscription->stripe_price,
            'quantity' => 1,
        ]);

        return $user;
    }

    /**
     * Create a steer listing with an active subscription
     */
    protected function createSteerWithSubscription(?User $user = null, array $steerAttributes = []): SteerListing
    {
        $user = $user ?? $this->createUserWithSubscription();
        
        $subscriptionId = 'sub_test_' . uniqid();
        
        // Create the listing
        $steer = SteerListing::factory()->create(array_merge([
            'user_id' => $user->id,
            'status' => 'active',
            'stripe_subscription_id' => $subscriptionId,
        ], $steerAttributes));

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'type' => 'steer_' . $steer->id,
            'stripe_id' => $subscriptionId,
            'stripe_status' => 'active',
            'stripe_price' => config('cashier.price_ids.steer_listing', 'price_test'),
            'quantity' => 1,
        ]);

        // Create subscription item
        SubscriptionItem::create([
            'subscription_id' => $subscription->id,
            'stripe_id' => 'si_test_' . uniqid(),
            'stripe_product' => 'prod_test',
            'stripe_price' => $subscription->stripe_price,
            'quantity' => 1,
        ]);

        return $steer;
    }

    /**
     * Create a stud listing with an active subscription
     */
    protected function createStudWithSubscription(?User $user = null, array $studAttributes = []): StudListing
    {
        $user = $user ?? $this->createUserWithSubscription();
        
        $subscriptionId = 'sub_test_' . uniqid();
        
        // Create the listing
        $stud = StudListing::factory()->create(array_merge([
            'user_id' => $user->id,
            'status' => 'active',
            'stripe_subscription_id' => $subscriptionId,
        ], $studAttributes));

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'type' => 'stud_' . $stud->id,
            'stripe_id' => $subscriptionId,
            'stripe_status' => 'active',
            'stripe_price' => config('cashier.price_ids.stud_listing', 'price_test'),
            'quantity' => 1,
        ]);

        // Create subscription item
        SubscriptionItem::create([
            'subscription_id' => $subscription->id,
            'stripe_id' => 'si_test_' . uniqid(),
            'stripe_product' => 'prod_test',
            'stripe_price' => $subscription->stripe_price,
            'quantity' => 1,
        ]);

        return $stud;
    }

    /**
     * Create a cancelled listing with subscription
     */
    protected function createCancelledListing(string $type = 'steer', ?User $user = null): SteerListing|StudListing
    {
        $user = $user ?? $this->createUserWithStripeId();
        $subscriptionId = 'sub_test_' . uniqid();
        
        if ($type === 'steer') {
            $listing = SteerListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'cancelled',
                'stripe_subscription_id' => $subscriptionId,
            ]);
            $subscriptionType = 'steer_' . $listing->id;
        } else {
            $listing = StudListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'cancelled',
                'stripe_subscription_id' => $subscriptionId,
            ]);
            $subscriptionType = 'stud_' . $listing->id;
        }

        // Create cancelled subscription
        Subscription::create([
            'user_id' => $user->id,
            'type' => $subscriptionType,
            'stripe_id' => $subscriptionId,
            'stripe_status' => 'canceled',
            'stripe_price' => config('cashier.price_ids.' . $type . '_listing', 'price_test'),
            'quantity' => 1,
            'ends_at' => now(),
        ]);

        return $listing;
    }

    /**
     * Create a listing on grace period (cancelled but still active)
     */
    protected function createGracePeriodListing(string $type = 'steer', ?User $user = null): SteerListing|StudListing
    {
        $user = $user ?? $this->createUserWithStripeId();
        $subscriptionId = 'sub_test_' . uniqid();
        
        if ($type === 'steer') {
            $listing = SteerListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'active', // Still active during grace period
                'stripe_subscription_id' => $subscriptionId,
            ]);
            $subscriptionType = 'steer_' . $listing->id;
        } else {
            $listing = StudListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'active',
                'stripe_subscription_id' => $subscriptionId,
            ]);
            $subscriptionType = 'stud_' . $listing->id;
        }

        // Create subscription on grace period
        Subscription::create([
            'user_id' => $user->id,
            'type' => $subscriptionType,
            'stripe_id' => $subscriptionId,
            'stripe_status' => 'active',
            'stripe_price' => config('cashier.price_ids.' . $type . '_listing', 'price_test'),
            'quantity' => 1,
            'ends_at' => now()->addDays(3), // Ends in 3 days
        ]);

        return $listing;
    }

    /**
     * Create multiple listings for a user
     */
    protected function createMultipleListings(User $user, array $types = ['steer', 'stud']): array
    {
        $listings = [];
        
        foreach ($types as $type) {
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $listings[$type][] = match($type) {
                    'steer' => SteerListing::factory()->create(['user_id' => $user->id]),
                    'stud' => StudListing::factory()->create(['user_id' => $user->id]),
                    'genetics' => \App\Models\GeneticsListing::factory()->create(['user_id' => $user->id]),
                    'equipment' => \App\Models\ShowEquipmentListing::factory()->create(['user_id' => $user->id]),
                };
            }
        }
        
        return $listings;
    }

    /**
     * Create a user with multiple active subscriptions
     */
    protected function createUserWithMultipleSubscriptions(int $steerCount = 2, int $studCount = 1): User
    {
        $user = $this->createUserWithStripeId();
        
        $listings = [];
        
        // Create steer listings with subscriptions
        for ($i = 0; $i < $steerCount; $i++) {
            $listings['steers'][] = $this->createSteerWithSubscription($user);
        }
        
        // Create stud listings with subscriptions
        for ($i = 0; $i < $studCount; $i++) {
            $listings['studs'][] = $this->createStudWithSubscription($user);
        }
        
        return $user;
    }

    /**
     * Create a listing with past due subscription
     */
    protected function createPastDueListing(string $type, ?User $user = null): SteerListing|StudListing
    {
        $user = $user ?? $this->createUserWithStripeId();
        $listing = $type === 'steer' 
            ? $this->createSteerWithSubscription($user)
            : $this->createStudWithSubscription($user);
        
        // Update subscription to be past due
        $subscription = $user->subscriptions()
            ->where('stripe_id', $listing->stripe_subscription_id)
            ->first();
            
        $subscription->update([
            'stripe_status' => 'past_due',
        ]);
        
        return $listing;
    }

    /**
     * Create a listing with trialing subscription
     */
    protected function createTrialingListing(string $type, ?User $user = null, int $trialDays = 7): SteerListing|StudListing
    {
        $user = $user ?? $this->createUserWithStripeId();
        
        $listing = match($type) {
            'steer' => SteerListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'active',
                'stripe_subscription_id' => 'sub_trial_' . uniqid(),
            ]),
            'stud' => StudListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'active',
                'stripe_subscription_id' => 'sub_trial_' . uniqid(),
            ]),
        };
        
        // Create trialing subscription
        Subscription::create([
            'user_id' => $user->id,
            'type' => $type . '_' . $listing->id,
            'stripe_id' => $listing->stripe_subscription_id,
            'stripe_status' => 'trialing',
            'stripe_price' => 'price_test_' . $type,
            'quantity' => 1,
            'trial_ends_at' => now()->addDays($trialDays),
        ]);
        
        return $listing;
    }
}