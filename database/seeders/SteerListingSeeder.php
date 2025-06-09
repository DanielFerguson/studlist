<?php

namespace Database\Seeders;

use App\Models\SteerListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class SteerListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some users first if they don't exist
        $users = User::count() > 0
            ? User::take(5)->get()
            : User::factory(5)->create();

        // Create listings for each user
        foreach ($users as $user) {
            // Create 2-5 regular listings per user
            SteerListing::factory()
                ->count(rand(2, 5))
                ->for($user)
                ->create();

            // Create 1 premium listing per user (if they have multiple listings)
            if (rand(1, 10) > 3) { // 70% chance
                SteerListing::factory()
                    ->premium()
                    ->for($user)
                    ->create();
            }

            // Create 1 listing without photos per user (for testing)
            if (rand(1, 10) > 7) { // 30% chance
                SteerListing::factory()
                    ->withoutPhotos()
                    ->for($user)
                    ->create();
            }
        }

        // Create some standalone listings with new users
        SteerListing::factory(10)->create();

        // Create a few premium listings
        SteerListing::factory(3)->premium()->create();

        // Create a few listings without photos
        SteerListing::factory(2)->withoutPhotos()->create();

        $this->command->info('Created '.SteerListing::count().' steer listings');
    }
}
