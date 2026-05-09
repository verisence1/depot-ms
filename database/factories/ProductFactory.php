<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            [
                'name' => 'Petrol',
                'category' => 'refined',
            ],
            [
                'name' => 'Diesel',
                'category' => 'refined',
            ],
            [
                'name' => 'Kerosene',
                'category' => 'refined',
            ],
            [
                'name' => 'LPG',
                'category' => 'lpg',
            ],
        ];

        $product = fake()->randomElement($products);

        return [
            'name' => $product['name'],

            'code' => strtoupper(
                fake()->unique()->lexify('PRD???')
            ),

            'category' => $product['category'],

            'flash_point' => fake()->randomFloat(
                2,
                10,
                100
            ),

            'density' => fake()->randomFloat(
                2,
                0.70,
                1.20
            ),
        ];
    }
}
