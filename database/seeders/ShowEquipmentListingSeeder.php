<?php

namespace Database\Seeders;

use App\Models\ShowEquipmentListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShowEquipmentListingSeeder extends Seeder
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
            // Create 1-3 equipment listings per user
            ShowEquipmentListing::factory()
                ->count(rand(1, 3))
                ->for($user)
                ->create();

            // Create new condition equipment (40% chance)
            if (rand(1, 10) > 6) {
                ShowEquipmentListing::factory()
                    ->new()
                    ->for($user)
                    ->create();
            }

            // Create used equipment (30% chance)
            if (rand(1, 10) > 7) {
                ShowEquipmentListing::factory()
                    ->for($user)
                    ->create(['condition' => 'Used']);
            }
        }

        // Create standalone listings with variety of conditions
        ShowEquipmentListing::factory()
            ->new()
            ->count(4)
            ->create();

        ShowEquipmentListing::factory()
            ->count(5)
            ->create(['condition' => 'Like New']);

        ShowEquipmentListing::factory()
            ->count(6)
            ->create(['condition' => 'Good']);

        ShowEquipmentListing::factory()
            ->count(3)
            ->create(['condition' => 'Fair']);

        ShowEquipmentListing::factory()
            ->count(2)
            ->create(['condition' => 'Poor']);

        // Create listings without photos
        ShowEquipmentListing::factory()
            ->count(4)
            ->create(['photos' => []]);

        // Create some complete show kits
        ShowEquipmentListing::factory()
            ->count(2)
            ->create([
                'title' => 'Complete Show Kit',
                'description' => 'Everything you need for show day including halters, leads, grooming supplies, and display stands.',
            ]);

        $this->command->info('Created '.ShowEquipmentListing::count().' show equipment listings');
    }
}