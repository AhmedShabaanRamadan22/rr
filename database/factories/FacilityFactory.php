<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_number' => fake()->unique()->randomNumber(),
            'name' => fake()->name() . ' facility' ,
            'version_date' => fake()->date(),
            'version_date_hj' => fake()->date(),
            'end_date' => fake()->date(),
            'end_date_hj' => fake()->date(),
            'registration_source' => City::inRandomOrder()->first()->id ?? City::factory(),
            'license' => fake()->randomNumber(),
            'license_expired' => fake()->date(),
            'license_expired_hj' => fake()->date(),
            'capacity' => fake()->randomNumber(),
            'tax_certificate' => fake()->randomNumber(),
            'employee_number' => fake()->randomNumber(),
            'building_number' => fake()->buildingNumber(),
            'postal_code' => fake()->numberBetween(10000, 999999),
            'street_name' => fake()->streetName(),

            'city_id' => City::inRandomOrder()->first()->id ?? City::factory(),
            'district_id' => District::inRandomOrder()->first()->id ?? District::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
