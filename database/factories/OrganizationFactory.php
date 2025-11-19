<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => $this->faker->words(2, true),
            'name_en' => $this->faker->words(2, true),
            'domain' => $this->faker->domainName(),
            'primary_color' => $this->faker->hexColor(),
        ];
    }
}
