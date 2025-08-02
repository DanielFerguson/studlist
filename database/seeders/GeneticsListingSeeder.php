<?php

namespace Database\Seeders;

use App\Models\GeneticsListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class GeneticsListingSeeder extends Seeder
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
            // Create 1-2 semen straw listings per user
            GeneticsListing::factory()
                ->semenStraws()
                ->count(rand(1, 2))
                ->for($user)
                ->create();

            // Create embryo listings (30% chance)
            if (rand(1, 10) > 7) {
                GeneticsListing::factory()
                    ->embryos()
                    ->for($user)
                    ->create();
            }

            // Create high-value genetics (20% chance)
            if (rand(1, 10) > 8) {
                GeneticsListing::factory()
                    ->for($user)
                    ->create(['price' => rand(500, 1000)]);
            }
        }

        // Create standalone listings with variety
        GeneticsListing::factory()
            ->semenStraws()
            ->count(6)
            ->create();

        GeneticsListing::factory()
            ->embryos()
            ->count(4)
            ->create();

        // Create some premium genetics
        GeneticsListing::factory()
            ->count(2)
            ->create(['price' => rand(800, 1200)]);

        // Create listings without photos
        GeneticsListing::factory()
            ->count(3)
            ->create(['photos' => []]);

        // Create some Wagyu genetics (high value)
        GeneticsListing::factory()
            ->count(2)
            ->create([
                'breed' => 'Wagyu',
                'price' => rand(800, 1500),
            ]);

        $this->command->info('Created '.GeneticsListing::count().' genetics listings');
    }
}