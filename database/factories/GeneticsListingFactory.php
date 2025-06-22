<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeneticsListing>
 */
class GeneticsListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $breeds = ['Angus', 'Hereford', 'Shorthorn', 'Charolais', 'Limousin', 'Wagyu', 'Other'];
        $types = ['Semen Straws', 'Embryos'];
        $states = ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA'];

        // Generate some bull names for genetics
        $bullNames = [
            'Elite Bull', 'Champion Sire', 'Premium Bull', 'Master Bull', 'Royal Bull',
            'Supreme Sire', 'Grand Champion', 'King Bull', 'Major Sire', 'Chief Bull',
            'Thunder Strike', 'Storm King', 'Rebel Chief', 'Ranger Elite', 'Scout Master',
        ];

        // Generate some dam names
        $damNames = [
            'Lady Elite', 'Premium Dam', 'Royal Cow', 'Queen Lady', 'Princess Dam',
            'Supreme Lady', 'Grand Dame', 'Master Cow', 'Elite Female', 'Champion Cow',
        ];

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement($bullNames).' '.$this->faker->numberBetween(1, 999),
            'price' => $this->faker->randomFloat(2, 50, 2000),
            'photos' => $this->faker->randomElements([
                'genetics-photos/genetics1.jpg',
                'genetics-photos/genetics2.jpg',
                'genetics-photos/genetics3.jpg',
                'genetics-photos/genetics4.jpg',
            ], $this->faker->numberBetween(1, 3)),
            'breed' => $this->faker->randomElement($breeds),
            'type' => $this->faker->randomElement($types),
            'sire' => $this->faker->randomElement($bullNames).' '.$this->faker->numberBetween(1, 999),
            'dam' => $this->faker->randomElement($damNames).' '.$this->faker->numberBetween(1, 999),
            'registration_link' => $this->faker->optional(0.7)->url(),
            'storage_location' => $this->faker->randomElement($states),
            'phone_contact' => $this->faker->optional(0.9)->randomElement([
                '0412345678', '0423456789', '0434567890', '0445678901',
                '0456789012', '0467890123', '0478901234', '0489012345',
            ]),
            'email_contact' => $this->faker->optional(0.8)->safeEmail(),
            'description' => $this->faker->optional(0.8)->sentence($this->faker->numberBetween(10, 30)),
        ];
    }

    /**
     * Create a genetics listing without photos for testing
     */
    public function withoutPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [],
        ]);
    }

    /**
     * Create a genetics listing with full photos
     */
    public function withFullPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [
                'genetics-photos/genetics1.jpg',
                'genetics-photos/genetics2.jpg',
                'genetics-photos/genetics3.jpg',
                'genetics-photos/genetics4.jpg',
            ],
        ]);
    }

    /**
     * Create a premium genetics listing
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'breed' => 'Wagyu',
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'sire' => 'Elite Wagyu Champion',
            'dam' => 'Premium Wagyu Dam',
            'type' => 'Semen Straws',
        ]);
    }

    /**
     * Create a semen straws listing
     */
    public function semenStraws(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Semen Straws',
            'price' => $this->faker->randomFloat(2, 50, 500),
        ]);
    }

    /**
     * Create an embryo listing
     */
    public function embryos(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Embryos',
            'price' => $this->faker->randomFloat(2, 200, 2000),
        ]);
    }
}
