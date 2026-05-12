<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $companies = [
            'Safari Energy Ltd',
            'Mansa Petroleum Ltd',
            'Nyali Logistics Ltd',
            'Kaunti Transporters Ltd',
            'Jambo Fuel Distributors',
            'Nairobi Cargo Services',
        ];

        $name = fake()->unique()->randomElement($companies);
        $domain = strtolower(str_replace([' ', 'Ltd'], ['', ''], $name)) . '.co.ke';

        return [
            'name' => $name,
            'tin' => 'TIN' . fake()->unique()->numerify('#########'),
            'contact_email' => "contact@{$domain}",
            'credit_limit' => fake()->randomFloat(
                2,
                200000,
                2000000
            ),
        ];
    }
}
