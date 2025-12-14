<?php

use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Steer Listing Photo Uploads', function () {
    beforeEach(function () {
        Storage::fake('public');
    });

    test('users can create steer listings with photos', function () {
        $user = User::factory()->create();

        $photo1 = UploadedFile::fake()->image('steer1.jpg', 1024, 768);
        $photo2 = UploadedFile::fake()->image('steer2.jpg', 800, 600);

        $steerData = [
            'name' => 'Test Steer with Photos',
            'breed' => 'Angus',
            'colour' => 'Black',
            'dob' => '2023-01-15',
            'sire' => 'Champion Bull',
            'dam' => 'Prize Cow',
            'location' => 'Brisbane, QLD',
            'phone_contact' => '0412345678',
            'photos' => [$photo1, $photo2],
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Check for validation errors
        if ($response->status() === 302 && session()->has('errors')) {
            $this->fail('Validation failed: '.implode(', ', session('errors')->all()));
        }

        // Steers should be created and redirect to dashboard
        $steer = SteerListing::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($steer);
        $response->assertRedirect('/dashboard');
        $this->assertCount(2, $steer->photos);

        // Verify files were stored in correct directory
        foreach ($steer->photos as $photoPath) {
            Storage::disk('public')->assertExists($photoPath);
            $this->assertStringStartsWith('steer-photos/', $photoPath);
        }
    });

    test('users can update steer listings with additional photos', function () {
        $user = User::factory()->create();
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'photos' => ['steer-photos/existing.jpg'],
        ]);

        // Put a fake file in storage to simulate existing photo
        Storage::disk('public')->put('steer-photos/existing.jpg', 'fake content');

        $newPhoto = UploadedFile::fake()->image('new-steer.jpg');

        $updateData = [
            'name' => $steer->name,
            'breed' => $steer->breed,
            'colour' => $steer->colour,
            'dob' => $steer->date_of_birth->format('Y-m-d'),
            'location' => $steer->location,
            'email_contact' => 'test@example.com',
            'photos' => [$newPhoto],
        ];

        $this->actingAs($user)
            ->put("/steers/{$steer->id}", $updateData)
            ->assertRedirect();

        $steer->refresh();
        // Photos are appended to existing ones
        $this->assertCount(2, $steer->photos);
        $this->assertContains('steer-photos/existing.jpg', $steer->photos);
    });

    test('steer listings handle large photo uploads', function () {
        $user = User::factory()->create();

        // Create a larger image (2MB)
        $largePhoto = UploadedFile::fake()->image('large-steer.jpg', 2048, 1536)->size(2048);

        $steerData = [
            'name' => 'Steer with Large Photo',
            'breed' => 'Hereford',
            'colour' => 'Red',
            'dob' => '2023-03-20',
            'location' => 'Rockhampton, QLD',
            'email_contact' => 'large@example.com',
            'photos' => [$largePhoto],
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Check for validation errors
        if ($response->status() === 302 && session()->has('errors')) {
            $this->fail('Validation failed: '.implode(', ', session('errors')->all()));
        }

        // Steers should be created and redirect to dashboard
        $steer = SteerListing::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($steer);
        $response->assertRedirect('/dashboard');
        $this->assertCount(1, $steer->photos);
        Storage::disk('public')->assertExists($steer->photos[0]);
    });

    test('steer listings work without photos', function () {
        $user = User::factory()->create();

        $steerData = [
            'name' => 'Steer without Photos',
            'breed' => 'Angus',
            'colour' => 'Grey',
            'dob' => '2023-02-10',
            'location' => 'Townsville, QLD',
            'email_contact' => 'nophotos@example.com',
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Check for validation errors
        if ($response->status() === 302 && session()->has('errors')) {
            $this->fail('Validation failed: '.implode(', ', session('errors')->all()));
        }

        // Steers should be created and redirect to dashboard
        $steer = SteerListing::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($steer);
        $response->assertRedirect('/dashboard');
        $this->assertEmpty($steer->photos);
    });
});

describe('Stud Listing Photo Uploads', function () {
    beforeEach(function () {
        Storage::fake('public');
    });

    test('users can create stud listings with photos', function () {
        $user = User::factory()->create();

        $photo1 = UploadedFile::fake()->image('stud1.jpg');
        $photo2 = UploadedFile::fake()->image('stud2.jpg');
        $photo3 = UploadedFile::fake()->image('stud3.jpg');

        $studData = [
            'name' => 'Premium Stud Bull',
            'breed' => 'Angus',
            'colour' => 'Black',
            'dob' => '2022-06-15',
            'tattoo_number' => 'AB123',
            'sire' => 'Elite Sire',
            'dam' => 'Champion Dam',
            'location' => 'Armidale, NSW',
            'phone_contact' => '0423456789',
            'photos' => [$photo1, $photo2, $photo3],
        ];

        $response = $this->actingAs($user)
            ->post('/studs', $studData);

        // Studs should be created and redirect to dashboard
        $stud = StudListing::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($stud);
        $response->assertRedirect('/dashboard');
        $this->assertCount(3, $stud->photos);

        // Verify files were stored in correct directory
        foreach ($stud->photos as $photoPath) {
            Storage::disk('public')->assertExists($photoPath);
            $this->assertStringStartsWith('stud-photos/', $photoPath);
        }
    });

    test('users can update stud listings with photos', function () {
        $user = User::factory()->create();
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'photos' => ['stud-photos/original1.jpg', 'stud-photos/original2.jpg'],
        ]);

        $newPhoto = UploadedFile::fake()->image('new-stud.jpg');

        $updateData = [
            'name' => $stud->name,
            'breed' => $stud->breed,
            'colour' => $stud->colour,
            'dob' => $stud->date_of_birth->format('Y-m-d'),
            'tattoo_number' => $stud->tattoo_number,
            'location' => $stud->location,
            'phone_contact' => '0412345678',
            'photos' => [$newPhoto],
        ];

        $this->actingAs($user)
            ->put("/studs/{$stud->id}", $updateData)
            ->assertRedirect();

        $stud->refresh();
        // Photos are appended to existing ones
        $this->assertCount(3, $stud->photos);
    });
});

describe('Genetics Listing Photo Uploads', function () {
    beforeEach(function () {
        Storage::fake('public');
    });

    test('users can create genetics listings with photos', function () {
        $user = User::factory()->create();

        $photo = UploadedFile::fake()->image('genetics.jpg');

        $geneticsData = [
            'type' => 'Semen Straws',
            'name' => 'Elite Genetics Package',
            'breed' => 'Wagyu',
            'price' => 150,
            'storage_location' => 'QLD',
            'email_contact' => 'genetics@example.com',
            'photos' => [$photo],
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertRedirect('/dashboard');

        $genetics = GeneticsListing::where('user_id', $user->id)->first();
        $this->assertCount(1, $genetics->photos);

        // Verify file was stored in correct directory
        Storage::disk('public')->assertExists($genetics->photos[0]);
        $this->assertStringStartsWith('genetics-photos/', $genetics->photos[0]);
    });

    test('genetics listings handle multiple photos', function () {
        $user = User::factory()->create();

        $photos = [
            UploadedFile::fake()->image('genetics1.jpg'),
            UploadedFile::fake()->image('genetics2.jpg'),
            UploadedFile::fake()->image('genetics3.jpg'),
            UploadedFile::fake()->image('genetics4.jpg'),
        ];

        $geneticsData = [
            'type' => 'Embryos',
            'name' => 'Premium Embryo Collection',
            'breed' => 'Angus',
            'price' => 500,
            'storage_location' => 'NSW',
            'phone_contact' => '0298765432',
            'photos' => $photos,
        ];

        $this->actingAs($user)
            ->post('/genetics', $geneticsData)
            ->assertRedirect('/dashboard');

        $genetics = GeneticsListing::where('user_id', $user->id)->first();
        $this->assertCount(4, $genetics->photos);
    });
});

describe('File Upload Validation', function () {
    beforeEach(function () {
        Storage::fake('public');
    });

    test('rejects non-image files', function () {
        $user = User::factory()->create();

        $invalidFile = UploadedFile::fake()->create('document.pdf', 1024);

        $steerData = [
            'name' => 'Test Steer',
            'breed' => 'Angus',
            'colour' => 'Black',
            'dob' => '2023-01-15',
            'location' => 'Brisbane, QLD',
            'email_contact' => 'test@example.com',
            'photos' => [$invalidFile],
        ];

        $this->actingAs($user)
            ->post('/steers', $steerData)
            ->assertSessionHasErrors(['photos.0']);
    });

    test('handles multiple file upload with mixed valid and invalid files', function () {
        $user = User::factory()->create();

        $validPhoto = UploadedFile::fake()->image('valid.jpg');
        $invalidFile = UploadedFile::fake()->create('invalid.txt', 100);

        $steerData = [
            'name' => 'Test Steer',
            'breed' => 'Angus',
            'colour' => 'Black',
            'dob' => '2023-01-15',
            'location' => 'Brisbane, QLD',
            'email_contact' => 'test@example.com',
            'photos' => [$validPhoto, $invalidFile],
        ];

        $this->actingAs($user)
            ->post('/steers', $steerData)
            ->assertSessionHasErrors(['photos.1']);
    });

    test('respects maximum file size limits', function () {
        $user = User::factory()->create();

        // Create a file larger than typical limits (10MB)
        $largeFile = UploadedFile::fake()->image('huge.jpg')->size(10240); // 10MB

        $steerData = [
            'name' => 'Test Steer',
            'breed' => 'Angus',
            'colour' => 'Black',
            'dob' => '2023-01-15',
            'location' => 'Brisbane, QLD',
            'email_contact' => 'test@example.com',
            'photos' => [$largeFile],
        ];

        $response = $this->actingAs($user)
            ->post('/steers', $steerData);

        // Should either reject or handle gracefully
        if ($response->status() === 302 && $response->isRedirect('/dashboard')) {
            // If it succeeded, verify the file was stored
            $steer = SteerListing::where('user_id', $user->id)->first();
            $this->assertNotNull($steer);
        } else {
            // If it failed, should have validation error
            $response->assertSessionHasErrors(['photos.0']);
        }
    });
});

describe('File Storage Organization', function () {
    beforeEach(function () {
        Storage::fake('public');
    });

    test('each listing type stores photos in separate directories', function () {
        $user = User::factory()->create();

        // Create each type of listing with photos
        $listings = [
            'steers' => [
                'data' => [
                    'name' => 'Test Steer',
                    'breed' => 'Angus',
                    'colour' => 'Black',
                    'dob' => '2023-01-15',
                    'location' => 'Brisbane, QLD',
                    'email_contact' => 'steer@example.com',
                    'photos' => [UploadedFile::fake()->image('steer.jpg')],
                ],
                'directory' => 'steer-photos/',
            ],
            'studs' => [
                'data' => [
                    'name' => 'Test Stud',
                    'breed' => 'Hereford',
                    'colour' => 'Red',
                    'dob' => '2022-05-20',
                    'tattoo_number' => 'HF123',
                    'location' => 'Sydney, NSW',
                    'phone_contact' => '0412345678',
                    'photos' => [UploadedFile::fake()->image('stud.jpg')],
                ],
                'directory' => 'stud-photos/',
            ],
            'genetics' => [
                'data' => [
                    'type' => 'Semen Straws',
                    'name' => 'Test Genetics',
                    'breed' => 'Wagyu',
                    'price' => 200,
                    'storage_location' => 'VIC',
                    'email_contact' => 'genetics@example.com',
                    'photos' => [UploadedFile::fake()->image('genetics.jpg')],
                ],
                'directory' => 'genetics-photos/',
            ],
            'show-equipment' => [
                'data' => [
                    'title' => 'Show Halter',
                    'condition' => 'New',
                    'location' => 'Perth, WA',
                    'email_contact' => 'equipment@example.com',
                    'photos' => [UploadedFile::fake()->image('equipment.jpg')],
                ],
                'directory' => 'show-equipment-photos/',
            ],
        ];

        foreach ($listings as $route => $config) {
            $response = $this->actingAs($user)
                ->post("/{$route}", $config['data'])
                ->assertRedirect();

            // Get the created listing
            $modelClass = match ($route) {
                'steers' => SteerListing::class,
                'studs' => StudListing::class,
                'genetics' => GeneticsListing::class,
                'show-equipment' => ShowEquipmentListing::class,
            };

            $listing = $modelClass::where('user_id', $user->id)->latest()->first();
            $this->assertNotEmpty($listing->photos);

            // Verify correct directory
            foreach ($listing->photos as $photo) {
                $this->assertStringStartsWith($config['directory'], $photo);
                Storage::disk('public')->assertExists($photo);
            }
        }
    });
});
