<?php

namespace Database\Factories;

use App\Models\Depot;
use App\Models\Product;
use App\Models\Tank;
use App\Models\Tanker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'depot_id' => Depot::factory(),

            'tank_id' => Tank::factory(),

            'product_id' => Product::factory(),

            'tanker_id' => Tanker::factory(),

            'volume' => fake()->randomFloat(
                2,
                1000,
                10000
            ),

            'receipt_date' => fake()->date(),

            'batch_ref' => strtoupper(
                fake()->unique()->bothify('BATCH-####')
            ),
            'status' => 'approved',
        ];
    }
}
