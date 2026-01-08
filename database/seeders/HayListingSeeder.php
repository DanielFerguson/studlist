<?php

namespace Database\Seeders;

use App\Models\HayListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class HayListingSeeder extends Seeder
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

        // Create hay listings for each user
        foreach ($users as $user) {
            // Basic hay listings with coordinates for map
            HayListing::factory()
                ->withCoordinates()
                ->count(rand(1, 2))
                ->for($user)
                ->create();

            // Listings with delivery (50% chance)
            if (rand(1, 10) > 5) {
                HayListing::factory()
                    ->withDelivery()
                    ->withCoordinates()
                    ->for($user)
                    ->create();
            }
        }

        // Create premium lucerne listings with coordinates
        HayListing::factory()
            ->premiumLucerne()
            ->withCoordinates()
            ->count(4)
            ->create();

        // Create listings with full test results and coordinates
        HayListing::factory()
            ->withTestResults()
            ->withCoordinates()
            ->count(5)
            ->create();

        // Create listings with delivery and coordinates
        HayListing::factory()
            ->withDelivery()
            ->withCoordinates()
            ->count(4)
            ->create();

        // Create per-bale priced listings with coordinates
        HayListing::factory()
            ->perBale()
            ->withCoordinates()
            ->count(3)
            ->create();

        // Create per-tonne priced listings with coordinates
        HayListing::factory()
            ->perTonne()
            ->withCoordinates()
            ->count(3)
            ->create();

        // Create some listings without photos
        HayListing::factory()
            ->withCoordinates()
            ->count(2)
            ->create(['photos' => []]);

        // Create a variety of hay types with coordinates for diverse map display
        $hayTypes = ['Lucerne', 'Grass', 'Oaten', 'Meadow', 'Wheaten', 'Mixed'];
        foreach ($hayTypes as $type) {
            HayListing::factory()
                ->withCoordinates()
                ->create([
                    'hay_type' => $type,
                    'title' => "Quality {$type} Hay - ".date('Y'),
                ]);
        }

        $this->command->info('Created '.HayListing::count().' hay listings');
    }
}
