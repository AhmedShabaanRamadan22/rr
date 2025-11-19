<?php

namespace Database\Seeders;

use App\Models\NationalityOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NationalityOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NationalityOrganization::create(['organization_id'=>1,'nationality_id'=>1]);
        NationalityOrganization::create(['organization_id'=>1,'nationality_id'=>2]);
        NationalityOrganization::create(['organization_id'=>2,'nationality_id'=>1]);
        NationalityOrganization::create(['organization_id'=>2,'nationality_id'=>2]);
    }
}
