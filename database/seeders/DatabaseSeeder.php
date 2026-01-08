<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        $this->command->info('');

        // Seed users first (includes admin and test users)
        $this->call([
            UserSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Seeding listings...');

        // Seed all listing types
        $this->call([
            SteerListingSeeder::class,
            StudListingSeeder::class,
            GeneticsListingSeeder::class,
            ShowEquipmentListingSeeder::class,
            HayListingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- Users: '.\App\Models\User::count());
        $this->command->info('- Steer Listings: '.\App\Models\SteerListing::count());
        $this->command->info('- Stud Listings: '.\App\Models\StudListing::count());
        $this->command->info('- Genetics Listings: '.\App\Models\GeneticsListing::count());
        $this->command->info('- Show Equipment Listings: '.\App\Models\ShowEquipmentListing::count());
        $this->command->info('- Hay Listings: '.\App\Models\HayListing::count());
        $this->command->info('');
        $this->command->info('You can log in with:');
        $this->command->info('Admin: admin@studlist.com / password');
        $this->command->info('User: john@example.com / password');
    }
}
