<?php

namespace Database\Seeders;

use App\Models\OrganizationService;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Service::create([
            'name_ar' => 'اعاشة',
            'name_en' => 'Catering',
            'price' => 20
        ]);
        Service::create([
            'name_ar' => 'توريد',
            'name_en' => 'Supply',
            'price' => 20
        ]);
        Service::create([
            'name_ar' => 'صيانة',
            'name_en' => 'Maintenance',
            'price' => 20
        ]);

        OrganizationService::create(['service_id' => 1, 'organization_id' => 1]);
        OrganizationService::create(['service_id' => 2, 'organization_id' => 1]);
        OrganizationService::create(['service_id' => 3, 'organization_id' => 1]);


        OrganizationService::create(['service_id' => 1, 'organization_id' => 2]);
        OrganizationService::create(['service_id' => 2, 'organization_id' => 2]);
        OrganizationService::create(['service_id' => 3, 'organization_id' => 2]);

        OrganizationService::create(['service_id' => 1, 'organization_id' => 3]);
        OrganizationService::create(['service_id' => 3, 'organization_id' => 3]);

    }
}
