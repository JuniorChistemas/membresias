<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'dni' => $this->faker->unique()->numerify('########'),
            'specialty' => $this->faker->word(),
            'phone' => $this->faker->numerify('########'),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'status' => $this->faker->boolean(),
        ];
    }
}
