<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TankerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'registration' => strtoupper(
                fake()->bothify('KAA-####')
            ),

            'type' => fake()->randomElement([
                'truck',
                'vessel',
                'pipeline',
            ]),

            'capacity_litres' => fake()->randomFloat(
                2,
                5000,
                40000
            ),

            'operator_id' => User::factory(),
        ];
    }
}
