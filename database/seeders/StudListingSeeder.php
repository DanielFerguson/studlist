<?php

namespace Database\Seeders;

use App\Models\StudListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudListingSeeder extends Seeder
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
            // Create 1-3 stud listings per user (studs are more premium, so fewer)
            StudListing::factory()
                ->count(rand(1, 3))
                ->for($user)
                ->create();

            // Create 1 active listing per user (50% chance)
            if (rand(1, 10) > 5) {
                StudListing::factory()
                    ->active()
                    ->for($user)
                    ->create();
            }

            // Create 1 listing without photos per user (20% chance)
            if (rand(1, 10) > 8) {
                StudListing::factory()
                    ->for($user)
                    ->create(['photos' => []]);
            }
        }

        // Create some standalone listings with new users
        StudListing::factory(8)->create();

        // Create a few active listings
        StudListing::factory(3)->active()->create();

        // Create a few listings without photos
        StudListing::factory(2)->create(['photos' => []]);

        // Create some high-quality stud listings with detailed info
        StudListing::factory(2)
            ->active()
            ->create([
                'registration_link' => 'https://angusaustralia.com.au/registration/12345',
            ]);

        $this->command->info('Created '.StudListing::count().' stud listings');
    }
}
