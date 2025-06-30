<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailySessionLog>
 */
class DailySessionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Sesión',
            'price' => 5.00,
            'payment_method_id' => $this->faker->numberBetween(1, 3), // Assuming you have 3 payment methods
            'registered_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
