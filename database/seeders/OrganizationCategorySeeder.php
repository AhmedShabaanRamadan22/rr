<?php

namespace Database\Seeders;

use App\Models\OrganizationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class OrganizationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationCategory::create(['category_id' => 1, 'organization_id' => 1]);
        OrganizationCategory::create(['category_id' => 2, 'organization_id' => 1]);
        OrganizationCategory::create(['category_id' => 1, 'organization_id' => 2]);
        OrganizationCategory::create(['category_id' => 2, 'organization_id' => 2]);
        OrganizationCategory::create(['category_id' => 3, 'organization_id' => 2]);

    }
}