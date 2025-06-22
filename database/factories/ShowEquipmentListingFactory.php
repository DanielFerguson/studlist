<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShowEquipmentListing>
 */
class ShowEquipmentListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];

        // Generate some show equipment titles
        $equipmentTypes = [
            'Show Halter', 'Show Stick', 'Show Box', 'Clippers', 'Blow Dryer', 'Show Blanket',
            'Show Brush Set', 'Hoof Pick', 'Show Stand', 'Hair Spray', 'Show Comb',
            'Fitting Chute', 'Show Ring', 'Grooming Table', 'Blocking Chute', 'Show Lead',
            'Scotch Comb', 'Rice Root Brush', 'Show Shears', 'Adhesive', 'Show Polish',
        ];

        // Generate some Australian towns with states
        $locations = [
            'Armidale, NSW', 'Tamworth, NSW', 'Orange, NSW', 'Dubbo, NSW',
            'Toowoomba, QLD', 'Roma, QLD', 'Charleville, QLD', 'Mount Isa, QLD',
            'Horsham, VIC', 'Hamilton, VIC', 'Warrnambool, VIC', 'Sale, VIC',
            'Murray Bridge, SA', 'Mount Gambier, SA', 'Port Augusta, SA',
            'Bunbury, WA', 'Geraldton, WA', 'Kalgoorlie, WA',
            'Devonport, TAS', 'Burnie, TAS',
        ];

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement($equipmentTypes).' - '.$this->faker->word(),
            'description' => $this->faker->optional(0.8)->sentence($this->faker->numberBetween(10, 50)),
            'photos' => $this->faker->randomElements([
                'show-equipment-photos/equipment1.jpg',
                'show-equipment-photos/equipment2.jpg',
                'show-equipment-photos/equipment3.jpg',
                'show-equipment-photos/equipment4.jpg',
            ], $this->faker->numberBetween(1, 4)),
            'condition' => $this->faker->randomElement($conditions),
            'location' => $this->faker->randomElement($locations),
            'phone_contact' => $this->faker->optional(0.9)->randomElement([
                '0412345678', '0423456789', '0434567890', '0445678901',
                '0456789012', '0467890123', '0478901234', '0489012345',
            ]),
            'email_contact' => $this->faker->optional(0.8)->safeEmail(),
        ];
    }

    /**
     * Create a show equipment listing without photos for testing
     */
    public function withoutPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [],
        ]);
    }

    /**
     * Create a show equipment listing with full photos
     */
    public function withFullPhotos(): static
    {
        return $this->state(fn (array $attributes) => [
            'photos' => [
                'show-equipment-photos/equipment1.jpg',
                'show-equipment-photos/equipment2.jpg',
                'show-equipment-photos/equipment3.jpg',
                'show-equipment-photos/equipment4.jpg',
            ],
        ]);
    }

    /**
     * Create a new condition listing
     */
    public function newCondition(): static
    {
        return $this->state(fn (array $attributes) => [
            'condition' => 'New',
        ]);
    }

    /**
     * Create a used condition listing
     */
    public function usedCondition(): static
    {
        return $this->state(fn (array $attributes) => [
            'condition' => $this->faker->randomElement(['Like New', 'Good', 'Fair']),
        ]);
    }
}