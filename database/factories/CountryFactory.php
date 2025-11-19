<?php

namespace Database\Factories;

use App\Models\Continent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->country();
        $code = $this->faker->countryCode();

        return [
            'name_ar' => $name,
            'name_en' => $name,
            'phone_code' => $code,
            'code' => $code,
            'iso3' => $code,
        ];
    }
}
