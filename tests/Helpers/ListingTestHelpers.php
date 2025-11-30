<?php

namespace Tests\Helpers;

use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ListingTestHelpers
{
    /**
     * Create a listing with standard test data
     */
    protected function createTestListing(string $type, ?User $user = null, array $attributes = []): SteerListing|StudListing|GeneticsListing|ShowEquipmentListing
    {
        $user = $user ?? User::factory()->create();

        switch ($type) {
            case 'steer':
                return SteerListing::factory()->create(array_merge([
                    'user_id' => $user->id,
                ], $attributes));

            case 'stud':
                return StudListing::factory()->create(array_merge([
                    'user_id' => $user->id,
                ], $attributes));

            case 'genetics':
                return GeneticsListing::factory()->create(array_merge([
                    'user_id' => $user->id,
                ], $attributes));

            case 'equipment':
                return ShowEquipmentListing::factory()->create(array_merge([
                    'user_id' => $user->id,
                ], $attributes));

            default:
                throw new \InvalidArgumentException("Invalid listing type: {$type}");
        }
    }

    /**
     * Get valid listing data for creation
     */
    protected function getValidListingData(string $type): array
    {
        switch ($type) {
            case 'steer':
                return [
                    'name' => 'Test Steer',
                    'breed' => 'Angus',
                    'colour' => 'Black',
                    'dob' => now()->subYear()->format('Y-m-d'),
                    'location' => 'Brisbane, QLD',
                    'email_contact' => 'test@example.com',
                ];

            case 'stud':
                return [
                    'name' => 'Test Stud',
                    'breed' => 'Hereford',
                    'colour' => 'Red',
                    'dob' => now()->subYears(2)->format('Y-m-d'),
                    'tattoo_number' => 'TEST123',
                    'location' => 'Sydney, NSW',
                    'phone_contact' => '0412345678',
                ];

            case 'genetics':
                return [
                    'type' => 'Semen Straws',
                    'name' => 'Test Genetics',
                    'breed' => 'Wagyu',
                    'price' => 250,
                    'storage_location' => 'QLD',
                    'email_contact' => 'genetics@example.com',
                ];

            case 'equipment':
                return [
                    'title' => 'Test Equipment',
                    'condition' => 'New',
                    'location' => 'Melbourne, VIC',
                    'email_contact' => 'equipment@example.com',
                ];

            default:
                throw new \InvalidArgumentException("Invalid listing type: {$type}");
        }
    }

    /**
     * Create listing with photos
     */
    protected function createListingWithPhotos(string $type, ?User $user = null, int $photoCount = 2): SteerListing|StudListing|GeneticsListing|ShowEquipmentListing
    {
        Storage::fake('public');

        $photos = [];
        for ($i = 1; $i <= $photoCount; $i++) {
            $photos[] = "test-photos/photo{$i}.jpg";
        }

        return $this->createTestListing($type, $user, ['photos' => $photos]);
    }

    /**
     * Get test photo uploads
     */
    protected function getTestPhotos(int $count = 2): array
    {
        $photos = [];
        for ($i = 1; $i <= $count; $i++) {
            $photos[] = UploadedFile::fake()->image("test{$i}.jpg");
        }

        return $photos;
    }

    /**
     * Assert listing was created successfully
     */
    protected function assertListingCreated(string $type, User $user, array $expectedData = []): void
    {
        $model = match ($type) {
            'steer' => SteerListing::class,
            'stud' => StudListing::class,
            'genetics' => GeneticsListing::class,
            'equipment' => ShowEquipmentListing::class,
        };

        $listing = $model::where('user_id', $user->id)->latest()->first();

        $this->assertNotNull($listing, "Expected {$type} listing to be created");

        foreach ($expectedData as $key => $value) {
            $this->assertEquals($value, $listing->{$key}, "Expected {$key} to be {$value}");
        }
    }

    /**
     * Get listing create route
     */
    protected function getListingCreateRoute(string $type): string
    {
        return match ($type) {
            'steer' => '/steers',
            'stud' => '/studs',
            'genetics' => '/genetics',
            'equipment' => '/show-equipment',
        };
    }

    /**
     * Get listing edit route
     */
    protected function getListingEditRoute(string $type, $listingId): string
    {
        return match ($type) {
            'steer' => "/steers/{$listingId}",
            'stud' => "/studs/{$listingId}",
            'genetics' => "/genetics/{$listingId}",
            'equipment' => "/show-equipment/{$listingId}",
        };
    }

    /**
     * Get listing delete route
     */
    protected function getListingDeleteRoute(string $type, $listingId): string
    {
        return $this->getListingEditRoute($type, $listingId);
    }
}
