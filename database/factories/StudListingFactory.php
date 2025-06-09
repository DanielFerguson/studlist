<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudListing>
 */
class StudListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->firstName.' Bull',
            'photos' => [],
            'date_of_birth' => $this->faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
            'breed' => $this->faker->randomElement(['Angus', 'Hereford', 'Shorthorn', 'Charolais', 'Limousin', 'Wagyu']),
            'colour' => $this->faker->randomElement(['Black', 'Red', 'White', 'Brown', 'Grey', 'Dun']),
            'tattoo_number' => $this->faker->optional()->bothify('##??##'),
            'location' => $this->faker->city.', '.$this->faker->stateAbbr,
            'sire' => $this->faker->optional()->firstName.' Bull',
            'dam' => $this->faker->optional()->firstName.' Cow',
            'registration_link' => $this->faker->optional()->url,
            'business_contact' => $this->faker->optional()->company,
            'phone_contact' => $this->faker->optional()->phoneNumber,
            'email_contact' => $this->faker->optional()->email,
            'pic_number' => $this->faker->optional()->bothify('?######'),
            'description' => $this->faker->optional()->text(200),
            'status' => 'draft',
            'stripe_subscription_id' => null,
        ];
    }

    /**
     * Indicate that the stud listing is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'stripe_subscription_id' => null,
        ]);
    }

    /**
     * Indicate that the stud listing is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'stripe_subscription_id' => 'sub_'.$this->faker->uuid,
        ]);
    }

    /**
     * Indicate that the stud listing is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'stripe_subscription_id' => 'sub_'.$this->faker->uuid,
        ]);
    }
}
