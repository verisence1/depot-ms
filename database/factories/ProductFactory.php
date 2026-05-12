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
                'code' => 'PMS',
                'category' => 'refined',
                'flash_point' => 43.00,
                'density' => 0.74,
            ],
            [
                'name' => 'Diesel',
                'code' => 'AGO',
                'category' => 'refined',
                'flash_point' => 52.00,
                'density' => 0.85,
            ],
            [
                'name' => 'Kerosene',
                'code' => 'DPK',
                'category' => 'refined',
                'flash_point' => 38.00,
                'density' => 0.81,
            ],
            [
                'name' => 'LPG',
                'code' => 'LPG',
                'category' => 'lpg',
                'flash_point' => -104.00,
                'density' => 0.54,
            ],
        ];

        $product = fake()->randomElement($products);

        return [
            'name' => $product['name'],
            'code' => $product['code'],
            'category' => $product['category'],
            'flash_point' => $product['flash_point'],
            'density' => $product['density'],
        ];
    }
}
