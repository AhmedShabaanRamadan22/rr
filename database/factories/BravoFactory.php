<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bravo>
 */
class BravoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->numberBetween(10,99),
            'code' => fake()->numberBetween(1, 10),
            'channel' => fake()->numberBetween(1, 10),
            'organization_id' => Organization::inRandomOrder()->first()->id ?? Organization::factory(),
            'given_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
