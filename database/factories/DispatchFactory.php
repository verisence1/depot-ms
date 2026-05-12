<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Depot;
use App\Models\Dispatch;
use App\Models\Product;
use App\Models\Tank;
use App\Models\Tanker;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'depot_id' => Depot::factory(),
            'tank_id' => Tank::factory(),
            'product_id' => Product::factory(),
            'tanker_id' => Tanker::factory(),
            'customer_id' => Customer::factory(),
            'volume' => fake()->randomFloat(2, 2000, 15000),
            'dispatch_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'waybill_no' => strtoupper(fake()->unique()->bothify('KEN-WB-####')),
            'status' => fake()->randomElement([
                Dispatch::STATUS_PENDING,
                Dispatch::STATUS_APPROVED,
            ]),
        ];
    }
}
