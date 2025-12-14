<?php

namespace Database\Factories;

use App\Models\ServiceListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceListing>
 */
class ServiceListingFactory extends Factory
{
    protected $model = ServiceListing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA'];

        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['Photographer', 'Fitter', 'Feeder', 'Other']),
            'abn' => fake()->numerify('## ### ### ###'),
            'business_name' => fake()->company(),
            'contact_name' => fake()->name(),
            'phone_contact' => fake()->phoneNumber(),
            'email_contact' => fake()->safeEmail(),
            'locations_covered' => fake()->randomElements($states, fake()->numberBetween(1, 4)),
            'links' => [fake()->url()],
            'description' => fake()->paragraph(),
        ];
    }
}

