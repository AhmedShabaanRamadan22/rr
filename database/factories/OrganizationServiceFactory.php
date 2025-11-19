<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationService>
 */
class OrganizationServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::inRandomOrder()->first()->id ?? Service::factory(),
            'organization_id' => Organization::inRandomOrder()->first()->id ?? Organization::factory(),
        ];
    }
}
