<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Depot',

            'location' => fake()->city(),

            'license_number' => strtoupper(fake()->bothify('LIC-####')),

            'status' => fake()->randomElement([
                'active',
                'inactive',
                'suspended',
            ]),
        ];
    }
}
