<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TankerFactory extends Factory
{
    public function definition(): array
    {
        $prefixes = ['KAA', 'KBA', 'KCA', 'KDA', 'KEA'];

        return [
            'registration' => strtoupper(
                fake()->randomElement($prefixes) . '-' . fake()->numerify('####')
            ),
            'type' => fake()->randomElement([
                'truck',
                'vessel',
                'pipeline',
            ]),
            'capacity_litres' => fake()->randomFloat(
                2,
                12000,
                60000
            ),
            'operator_id' => User::factory(),
        ];
    }
}
