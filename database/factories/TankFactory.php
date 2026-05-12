<?php

namespace Database\Factories;

use App\Models\Depot;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class TankFactory extends Factory
{
    public function definition(): array
    {
        $capacity = fake()->randomFloat(2, 50000, 250000);
        $currentVolume = fake()->randomFloat(
            2,
            $capacity * 0.3,
            $capacity
        );

        return [
            'depot_id' => Depot::factory(),
            'product_id' => Product::factory(),
            'tag' => strtoupper(fake()->bothify('TK-###')),
            'capacity_litres' => $capacity,
            'current_volume' => min($currentVolume, $capacity),
        ];
    }
}
