<?php

namespace Database\Factories;

use App\Enums\BaleType;
use App\Enums\HayPriceType;
use App\Enums\HayQualityGrade;
use App\Enums\HayType;
use App\Enums\NitrateLevel;
use App\Enums\SeasonCut;
use App\Enums\StorageType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HayListing>
 */
class HayListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hayTypes = array_column(HayType::cases(), 'value');
        $baleTypes = array_column(BaleType::cases(), 'value');
        $qualityGrades = array_column(HayQualityGrade::cases(), 'value');
        $seasonCuts = array_column(SeasonCut::cases(), 'value');
        $storageTypes = array_column(StorageType::cases(), 'value');
        $priceTypes = array_column(HayPriceType::cases(), 'value');
        $nitrateLevels = array_column(NitrateLevel::cases(), 'value');

        $hayType = $this->faker->randomElement($hayTypes);
        $priceType = $this->faker->randomElement($priceTypes);

        $locations = [
            'Tamworth, NSW', 'Dubbo, NSW', 'Wagga Wagga, NSW', 'Orange, NSW',
            'Toowoomba, QLD', 'Roma, QLD', 'Longreach, QLD', 'Emerald, QLD',
            'Ballarat, VIC', 'Bendigo, VIC', 'Shepparton, VIC', 'Horsham, VIC',
            'Mount Gambier, SA', 'Port Augusta, SA', 'Murray Bridge, SA',
            'Bunbury, WA', 'Geraldton, WA', 'Kalgoorlie, WA',
            'Launceston, TAS', 'Burnie, TAS',
        ];

        return [
            'user_id' => User::factory(),
            'title' => $this->generateTitle($hayType),
            'hay_type' => $hayType,
            'bale_type' => $this->faker->randomElement($baleTypes),
            'quantity' => $this->faker->numberBetween(10, 2000),
            'weight_per_bale' => $this->faker->optional(0.7)->randomFloat(2, 20, 600),
            'season_cut' => $this->faker->optional(0.8)->randomElement($seasonCuts),
            'cut_year' => $this->faker->optional(0.9)->numberBetween(date('Y') - 2, date('Y')),
            'quality_grade' => $this->faker->optional(0.7)->randomElement($qualityGrades),
            'test_results_available' => $this->faker->boolean(30),
            'protein_percentage' => $this->faker->optional(0.3)->randomFloat(2, 8, 28),
            'moisture_percentage' => $this->faker->optional(0.3)->randomFloat(2, 8, 18),
            'energy_mj_kg' => $this->faker->optional(0.3)->randomFloat(2, 7, 12),
            'nitrate_level' => $this->faker->optional(0.3)->randomElement($nitrateLevels),
            'weather_damaged' => $this->faker->boolean(10),
            'storage_type' => $this->faker->randomElement($storageTypes),
            'location' => $this->faker->randomElement($locations),
            'latitude' => $this->faker->optional(0.3)->latitude(-44, -10),
            'longitude' => $this->faker->optional(0.3)->longitude(112, 154),
            'delivery_available' => $this->faker->boolean(60),
            'delivery_radius_km' => $this->faker->optional(0.5)->numberBetween(20, 500),
            'minimum_order_quantity' => $this->faker->optional(0.4)->numberBetween(5, 50),
            'price_type' => $priceType,
            'price_per_bale' => $priceType === 'Per Bale' ? $this->faker->randomFloat(2, 5, 200) : null,
            'price_per_tonne' => $priceType === 'Per Tonne' ? $this->faker->randomFloat(2, 150, 500) : null,
            'business_contact' => $this->faker->optional(0.6)->company(),
            'phone_contact' => $this->faker->optional(0.9)->randomElement([
                '0412345678', '0423456789', '0434567890', '0445678901',
                '0456789012', '0467890123', '0478901234', '0489012345',
            ]),
            'email_contact' => $this->faker->optional(0.7)->safeEmail(),
            'pic_number' => $this->faker->optional(0.5)->regexify('[A-Z]{4}[0-9]{4}'),
            'photos' => $this->faker->randomElements([
                'hay-photos/hay1.jpg',
                'hay-photos/hay2.jpg',
                'hay-photos/hay3.jpg',
            ], $this->faker->numberBetween(0, 3)),
            'description' => $this->faker->optional(0.7)->paragraph(),
        ];
    }

    private function generateTitle(string $hayType): string
    {
        $adjectives = ['Premium', 'Quality', 'Fresh', 'Excellent', 'Top Grade', 'First Class'];
        $year = $this->faker->numberBetween(date('Y') - 1, date('Y'));

        return $this->faker->randomElement($adjectives).' '.$hayType.' Hay - '.$year;
    }

    /**
     * Create a hay listing with test results
     */
    public function withTestResults(): static
    {
        return $this->state(fn (array $attributes) => [
            'test_results_available' => true,
            'protein_percentage' => $this->faker->randomFloat(2, 12, 24),
            'moisture_percentage' => $this->faker->randomFloat(2, 10, 14),
            'energy_mj_kg' => $this->faker->randomFloat(2, 8, 11),
            'nitrate_level' => $this->faker->randomElement(['Low', 'Medium']),
        ]);
    }

    /**
     * Create a premium lucerne hay listing
     */
    public function premiumLucerne(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Premium Lucerne Hay - '.date('Y'),
            'hay_type' => 'Lucerne',
            'quality_grade' => 'Premium',
            'test_results_available' => true,
            'protein_percentage' => $this->faker->randomFloat(2, 18, 24),
            'storage_type' => 'Shed',
            'weather_damaged' => false,
        ]);
    }

    /**
     * Create a hay listing with delivery
     */
    public function withDelivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_available' => true,
            'delivery_radius_km' => $this->faker->numberBetween(50, 300),
        ]);
    }

    /**
     * Create a hay listing with coordinates for map display
     */
    public function withCoordinates(): static
    {
        $australianCoords = [
            ['lat' => -31.2532, 'lng' => 150.9267], // Tamworth
            ['lat' => -32.2569, 'lng' => 148.6011], // Dubbo
            ['lat' => -35.1082, 'lng' => 147.3598], // Wagga Wagga
            ['lat' => -27.4698, 'lng' => 153.0251], // Brisbane area
            ['lat' => -37.5622, 'lng' => 143.8503], // Ballarat
            ['lat' => -34.9285, 'lng' => 138.6007], // Adelaide area
        ];

        $coords = $this->faker->randomElement($australianCoords);

        return $this->state(fn (array $attributes) => [
            'latitude' => $coords['lat'],
            'longitude' => $coords['lng'],
        ]);
    }

    /**
     * Create a hay listing priced per bale
     */
    public function perBale(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_type' => 'Per Bale',
            'price_per_bale' => $this->faker->randomFloat(2, 10, 200),
            'price_per_tonne' => null,
        ]);
    }

    /**
     * Create a hay listing priced per tonne
     */
    public function perTonne(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_type' => 'Per Tonne',
            'price_per_bale' => null,
            'price_per_tonne' => $this->faker->randomFloat(2, 200, 500),
        ]);
    }
}
