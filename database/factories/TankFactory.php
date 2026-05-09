<?php

namespace Database\Factories;

use App\Models\Depot;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class TankFactory extends Factory
{
    public function definition(): array
    {
        $capacity = fake()->randomFloat(2, 5000, 50000);

        return [
            'depot_id' => Depot::factory(),

            'product_id' => Product::factory(),

            'tag' => strtoupper(fake()->bothify('TNK-###')),

            'capacity_litres' => $capacity,

            'current_volume' => fake()->randomFloat(
                2,
                0,
                $capacity
            ),
        ];
    }
}
