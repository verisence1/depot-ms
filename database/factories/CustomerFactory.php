<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),

            'tin' => strtoupper(
                fake()->unique()->bothify('TIN####')
            ),

            'contact_email' => fake()->companyEmail(),

            'credit_limit' => fake()->randomFloat(
                2,
                10000,
                500000
            ),
        ];
    }
}
