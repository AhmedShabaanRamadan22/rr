<?php

namespace Database\Seeders;

use App\Models\FacilityService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilityServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        FacilityService::create(['service_id' => 1, 'facility_id' => 1]);
        FacilityService::create(['service_id' => 1, 'facility_id' => 3]);
        FacilityService::create(['service_id' => 3, 'facility_id' => 3]);
        FacilityService::create(['service_id' => 2, 'facility_id' => 2]);
        FacilityService::create(['service_id' => 2, 'facility_id' => 3]);
        FacilityService::create(['service_id' => 3, 'facility_id' => 1]);
    }
}
