<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company().' Energy',
            'legal_name' => $this->faker->company().' S.p.A.',
            'country' => $this->faker->randomElement(['IT', 'FR', 'DE', 'ES', 'NL']),
            'region' => $this->faker->state(),
            'generation_mix' => [
                'solar' => $this->faker->numberBetween(0, 60),
                'wind' => $this->faker->numberBetween(0, 40),
                'hydro' => $this->faker->numberBetween(0, 30),
                'gas' => $this->faker->numberBetween(0, 40),
            ],
            'status' => $this->faker->randomElement(['pending_kyc', 'active']),
        ];
    }

    public function active(): self
    {
        return $this->state(fn () => ['status' => 'active']);
    }
}
