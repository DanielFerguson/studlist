<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SteerListing>
 */
class SteerListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $breeds = ['Angus', 'Hereford', 'Shorthorn', 'Charolais', 'Limousin', 'Wagyu', 'Other'];
        $colours = ['Black', 'Red', 'White', 'Brown', 'Grey', 'Dun', 'Other'];

        // Generate some Australian-sounding steer names
        $steerNames = [
            'Bluey', 'Rusty', 'Duke', 'King', 'Major', 'Chief', 'Thunder', 'Storm',
            'Rebel', 'Ranger', 'Scout', 'Trooper', 'Bandit', 'Maverick', 'Hunter',
            'Copper', 'Steel', 'Iron', 'Coal', 'Ash', 'Blaze', 'Flash', 'Bolt',
        ];

        // Generate some Australian towns
        $towns = [
            'Armidale, NSW', 'Tamworth, NSW', 'Orange, NSW', 'Dubbo, NSW',
            'Toowoomba, QLD', 'Roma, QLD', 'Charleville, QLD', 'Mount Isa, QLD',
            'Horsham, VIC', 'Hamilton, VIC', 'Warrnambool, VIC', 'Sale, VIC',
            'Murray Bridge, SA', 'Mount Gambier, SA', 'Port Augusta, SA',
            'Bunbury, WA', 'Geraldton, WA', 'Kalgoorlie, WA',
            'Devonport, TAS', 'Burnie, TAS',
        ];

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement($steerNames).' '.$this->faker->numberBetween(1, 999),
            'photos' => $this->faker->randomElements([
                'steer-photos/steer1.jpg',
                'steer-photos/steer2.jpg',
                'steer-photos/steer3.jpg',
                'steer-photos/steer4.jpg',
                'steer-photos/steer5.jpg',
            ], $this->faker->numberBetween(1, 4)),
            'date_of_birth' => $this->faker->dateTimeBetween('-3 years', '-6 months')->format('Y-m-d'),
            'breed' => $this->faker->randomElement($breeds),
            'colour' => $this->faker->randomElement($colours),
            'location' => $this->faker->randomElement($towns),
            'sire' => $this->faker->optional(0.7)->randomElement([
                'Champion Bull 123', 'Premium Sire X', 'Elite Bull Y', 'Grand Champion Z',
                'Supreme Bull A', 'Master Bull B', 'Royal Bull C',
            ]),
            'dam' => $this->faker->optional(0.7)->randomElement([
                'Lady Cow 456', 'Premium Dam X', 'Elite Cow Y', 'Grand Lady Z',
                'Supreme Cow A', 'Master Cow B', 'Royal Cow C',
            ]),
            'business_contact' => $this->faker->optional(0.5)->company(),
            'email_contact' => $this->faker->optional(0.8)->safeEmail(),
            'phone_contact' => $this->faker->optional(0.9)->randomElement([
                '0412345678', '0423456789', '0434567890', '0445678901',
                '0456789012', '0467890123', '0478901234', '0489012345',
            ]),
            'pic_number' => $this->faker->optional(0.6)->regexify('[A-Z]{2}[0-9]{6}'),
            'description' => $this->faker->optional(0.8)->sentence($this->faker->numberBetween(10, 30)),
            'started_on_feed' => $this->faker->boolean(30), // 30% chance of being on feed
            'price' => $this->faker->optional(0.9)->randomFloat(2, 1000, 5000),
            'status' => 'draft', // Default to draft status
            'stripe_subscription_id' => $this->faker->optional(0.3)->regexify('sub_[A-Za-z0-9]{24}'),
        ];
    }

    /**
     * Create a steer listing without photos for testing
     */
    public function withoutPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [],
        ]);
    }

    /**
     * Create a steer listing with full photos
     */
    public function withFullPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [
                'steer-photos/steer1.jpg',
                'steer-photos/steer2.jpg',
                'steer-photos/steer3.jpg',
                'steer-photos/steer4.jpg',
                'steer-photos/steer5.jpg',
            ],
        ]);
    }

    /**
     * Create a premium steer listing
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'breed' => 'Wagyu',
            'price' => $this->faker->randomFloat(2, 3000, 8000),
            'sire' => 'Champion Wagyu Bull',
            'dam' => 'Elite Wagyu Cow',
            'started_on_feed' => true,
        ]);
    }

    /**
     * Create a draft steer listing (unpaid)
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'stripe_subscription_id' => null,
        ]);
    }

    /**
     * Create an active steer listing (paid)
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'stripe_subscription_id' => $this->faker->regexify('sub_[A-Za-z0-9]{24}'),
        ]);
    }
}
