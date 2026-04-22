<?php

namespace Database\Factories;

use App\Models\Broker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Broker>
 */
class BrokerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company().' Brokers',
            'legal_name' => $this->faker->company().' Ltd.',
            'country' => $this->faker->randomElement(['IT', 'FR', 'DE', 'ES', 'NL']),
            'default_commission_rate' => $this->faker->randomFloat(4, 0.01, 0.05),
            'status' => $this->faker->randomElement(['pending_kyc', 'active']),
        ];
    }

    public function active(): self
    {
        return $this->state(fn () => ['status' => 'active']);
    }
}
