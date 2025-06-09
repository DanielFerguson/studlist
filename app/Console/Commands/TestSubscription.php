<?php

namespace App\Console\Commands;

use App\Models\SteerListing;
use App\Models\User;
use Illuminate\Console\Command;

class TestSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:subscription {user_id} {steer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test subscription creation for a steer listing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $steerId = $this->argument('steer_id');

        $user = User::find($userId);
        $steer = SteerListing::find($steerId);

        if (! $user) {
            $this->error("User with ID {$userId} not found");

            return 1;
        }

        if (! $steer) {
            $this->error("Steer listing with ID {$steerId} not found");

            return 1;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Steer: {$steer->name} (Status: {$steer->status})");

        // Check if user has Stripe customer ID
        if (! $user->hasStripeId()) {
            $this->info('Creating Stripe customer...');
            $user->createAsStripeCustomer();
            $user->save();
        }

        $this->info("Stripe Customer ID: {$user->stripe_id}");

        // Check subscriptions in database
        $subscriptions = $user->subscriptions;
        $this->info('Subscriptions in database: '.$subscriptions->count());

        foreach ($subscriptions as $subscription) {
            $this->info("- Subscription: {$subscription->name} (Stripe ID: {$subscription->stripe_id}, Status: {$subscription->stripe_status})");
        }

        // Check if steer has subscription info
        $steerSubscription = $steer->laravelSubscription();
        if ($steerSubscription) {
            $this->info("Steer has subscription: {$steerSubscription->stripe_id} (Status: {$steerSubscription->stripe_status})");
        } else {
            $this->info('Steer has no subscription in database');
        }

        return 0;
    }
}
