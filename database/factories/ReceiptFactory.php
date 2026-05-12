<?php

namespace Database\Factories;

use App\Models\Depot;
use App\Models\Product;
use App\Models\Receipt;
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
            'volume' => fake()->randomFloat(2, 2000, 20000),
            'receipt_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'batch_ref' => strtoupper(fake()->unique()->bothify('KEN-BATCH-####')),
            'status' => fake()->randomElement([
                Receipt::STATUS_PENDING,
                Receipt::STATUS_APPROVED,
            ]),
        ];
    }
}
