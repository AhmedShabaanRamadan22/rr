<?php

namespace Database\Seeders;

use App\Models\FineOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FineOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FineOrganization::create([
            'description' => 'تأخر في تسليم الوجبات الطعامية اللذيذة الحارة ',
            'price' => '1',
            'organization_id' => '1',
            'fine_bank_id' => '1',

        ]);
    }
}
