<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tariff>
 */
class TariffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->randomElement(['GreenFix 12', 'IndexEU', 'Fixed Pro', 'Variable Flex']),
            'type' => $this->faker->randomElement(['fixed', 'variable', 'indexed']),
            'price_per_kwh' => $this->faker->randomFloat(4, 0.08, 0.35),
            'currency' => 'EUR',
            'green_percentage' => $this->faker->numberBetween(0, 100),
            'contract_length_months' => $this->faker->randomElement([12, 24, 36]),
            'min_annual_kwh' => $this->faker->randomElement([null, 50_000, 100_000, 500_000]),
            'region' => $this->faker->optional()->state(),
            'available_from' => now()->toDateString(),
            'available_to' => null,
            'active' => true,
        ];
    }
}
