<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Depot;
use App\Models\Dispatch;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Tank;
use App\Models\Tanker;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(5)->create();

        $depots = Depot::factory(2)->create();

        // $products = Product::factory(3)->create();

        $products = collect([
        Product::create([
            'name' => 'Petrol',
            'code' => 'PMS',
            'category' => 'refined',
            'flash_point' => 43.00,
            'density' => 0.74,
        ]),

        Product::create([
            'name' => 'Diesel',
            'code' => 'AGO',
            'category' => 'refined',
            'flash_point' => 52.00,
            'density' => 0.85,
        ]),

        Product::create([
            'name' => 'Kerosene',
            'code' => 'DPK',
            'category' => 'refined',
            'flash_point' => 38.00,
            'density' => 0.81,
        ]),

        Product::create([
            'name' => 'LPG',
            'code' => 'LPG',
            'category' => 'lpg',
            'flash_point' => -104.00,
            'density' => 0.54,
        ]),
    ]);

        $customers = Customer::factory(5)->create();

        foreach ($depots as $depot) {

            $depot->users()->attach(
                $users->random(2)->pluck('id')
            );

            Tank::factory(3)
                ->create([
                    'depot_id' => $depot->id,
                    'product_id' => $products->random()->id,
                ]);
        }

        $tankers = Tanker::factory(5)->create([
            'operator_id' => $users->random()->id,
        ]);

        $tanks = Tank::all();

        foreach (range(1, 10) as $i) {

            $tank = $tanks->random();

            Receipt::factory()->create([
                'depot_id' => $tank->depot_id,
                'tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'tanker_id' => $tankers->random()->id,
            ]);
        }

        foreach (range(1, 5) as $i) {

            $tank = $tanks->random();

            Dispatch::factory()->create([
                'depot_id' => $tank->depot_id,
                'tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'tanker_id' => $tankers->random()->id,
                'customer_id' => $customers->random()->id,
            ]);
        }
    }
}
