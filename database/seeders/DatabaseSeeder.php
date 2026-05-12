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

        $depots = collect([
            Depot::create([
                'name' => 'Nairobi Central Bulk Depot',
                'location' => 'Nairobi',
                'license_number' => 'EPRA-10245',
                'status' => 'active',
            ]),
            Depot::create([
                'name' => 'Mombasa Fuel Storage Terminal',
                'location' => 'Mombasa',
                'license_number' => 'EPRA-20418',
                'status' => 'active',
            ]),
        ]);

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

        $customers = collect([
            Customer::create([
                'name' => 'Safari Energy Ltd',
                'tin' => 'TIN123456789',
                'contact_email' => 'sales@safarienergy.co.ke',
                'credit_limit' => 1500000,
            ]),
            Customer::create([
                'name' => 'Mansa Petroleum Ltd',
                'tin' => 'TIN987654321',
                'contact_email' => 'accounts@mansaspetroleum.co.ke',
                'credit_limit' => 1200000,
            ]),
            Customer::create([
                'name' => 'Jambo Fuel Distributors',
                'tin' => 'TIN246810121',
                'contact_email' => 'info@jambofuel.co.ke',
                'credit_limit' => 900000,
            ]),
            Customer::create([
                'name' => 'Kaunti Transporters Ltd',
                'tin' => 'TIN135791113',
                'contact_email' => 'contact@kauntitransport.co.ke',
                'credit_limit' => 750000,
            ]),
            Customer::create([
                'name' => 'Nyali Logistics Ltd',
                'tin' => 'TIN112233445',
                'contact_email' => 'operations@nyalilogistics.co.ke',
                'credit_limit' => 680000,
            ]),
        ]);

        foreach ($depots as $depot) {
            $depot->users()->attach($users->random(2)->pluck('id'));

            Tank::factory(3)->create([
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

        foreach (range(1, 7) as $i) {
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
