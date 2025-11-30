<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Subscription;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin users
        $admin1 = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@studlist.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $admin2 = User::factory()->create([
            'name' => 'Support Admin',
            'email' => 'support@studlist.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create regular test users with known credentials
        $testUser1 = User::factory()->create([
            'name' => 'John Farmer',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $testUser2 = User::factory()->create([
            'name' => 'Jane Rancher',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create user with Stripe customer ID (for testing subscriptions)
        $stripeUser = User::factory()->create([
            'name' => 'Premium User',
            'email' => 'premium@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'stripe_id' => 'cus_test_'.uniqid(),
        ]);

        // Create users with different verification states
        $unverifiedUser = User::factory()->create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => null,
        ]);

        // Create bulk users with various attributes
        $bulkUsers = User::factory(20)->create()->each(function ($user, $index) {
            // 30% of users have Stripe customer IDs
            if ($index % 3 === 0) {
                $user->update(['stripe_id' => 'cus_test_'.uniqid()]);
            }

            // 20% of users are unverified
            if ($index % 5 === 0) {
                $user->update(['email_verified_at' => null]);
            }
        });

        // Create users from different Australian states
        $states = ['NSW', 'QLD', 'VIC', 'SA', 'WA', 'TAS', 'NT', 'ACT'];
        foreach ($states as $state) {
            User::factory()->create([
                'name' => fake()->name()." from $state",
                'email' => strtolower($state).'@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Create some users with active subscriptions (mock data)
        $subscribedUsers = User::factory(3)->create([
            'stripe_id' => fn () => 'cus_test_'.uniqid(),
        ])->each(function ($user) {
            // Create mock subscription records
            Subscription::create([
                'user_id' => $user->id,
                'type' => 'default',
                'stripe_id' => 'sub_test_'.uniqid(),
                'stripe_status' => 'active',
                'stripe_price' => config('cashier.steer_listing_price_id', 'price_test'),
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        });

        $this->command->info('Created '.User::count().' users including:');
        $this->command->info('- '.User::where('is_admin', true)->count().' admin users');
        $this->command->info('- '.User::whereNotNull('stripe_id')->count().' users with Stripe customer IDs');
        $this->command->info('- '.User::whereNull('email_verified_at')->count().' unverified users');
        $this->command->info('- '.Subscription::count().' active subscriptions');

        $this->command->info('');
        $this->command->info('Test credentials:');
        $this->command->info('Admin: admin@studlist.com / password');
        $this->command->info('User: john@example.com / password');
    }
}
