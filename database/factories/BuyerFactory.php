<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buyer>
 */
class BuyerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'vat_number' => strtoupper($this->faker->bothify('??#########')),
            'industry' => $this->faker->randomElement([
                'manufacturing',
                'retail',
                'hospitality',
                'logistics',
                'agriculture',
                'technology',
            ]),
            'country' => $this->faker->randomElement(['IT', 'FR', 'DE', 'ES', 'NL']),
            'annual_consumption_kwh' => $this->faker->numberBetween(50_000, 5_000_000),
        ];
    }
}
