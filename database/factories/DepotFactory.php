<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepotFactory extends Factory
{
    public function definition(): array
    {
        $locations = [
            'Nairobi',
            'Mombasa',
            'Kisumu',
            'Nakuru',
            'Eldoret',
        ];

        $names = [
            'Central Bulk Depot',
            'Fuel Storage Terminal',
            'Distribution Centre',
            'Aviation Fuel Hub',
            'Regional Storage Depot',
        ];

        $location = fake()->randomElement($locations);

        return [
            'name' => "{$location} " . fake()->randomElement($names),
            'location' => $location,
            'license_number' => 'EPRA-' . fake()->numerify('#####'),
            'status' => fake()->randomElement([
                'active',
                'inactive',
                'suspended',
            ]),
        ];
    }
}
