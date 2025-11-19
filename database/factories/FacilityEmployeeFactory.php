<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\FacilityEmployeePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityEmployee>
 */
class FacilityEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name() . ' Cooker',
            'facility_id' => Facility::inRandomOrder()->first()->id ?? Facility::factory(),
            'facility_employee_position_id' => FacilityEmployeePosition::inRandomOrder()->first()->id ?? FacilityEmployeePosition::factory(),
            'national_id' => fake()->numerify('1#########'),
        ];
    }
}
