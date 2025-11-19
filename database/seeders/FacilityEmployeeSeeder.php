<?php

namespace Database\Seeders;

use App\Models\FacilityEmployee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilityEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        FacilityEmployee::create([
            'facility_id' => 1,
            'facility_employee_position_id' => 1,
            'name' => 'محمد الحربي',
            'national_id' => '1001111111'
        ]);
        FacilityEmployee::create([
            'facility_id' => 1,
            'facility_employee_position_id' => 2,
            'name' => 'عبدالله بخاري',
            'national_id' => '1001111112'
        ]);
        FacilityEmployee::create([
            'facility_id' => 1,
            'facility_employee_position_id' => 3,
            'name' => 'سلطان خالد',
            'national_id' => '1001111113'
        ]);
        FacilityEmployee::create([
            'facility_id' => 1,
            'facility_employee_position_id' => 4,
            'name' => 'عمر العتيبي',
            'national_id' => '1001111114'
        ]);
        FacilityEmployee::create([
            'facility_id' => 2,
            'facility_employee_position_id' => 1,
            'name' => 'جواد الجهني',
            'national_id' => '1001111115'
        ]);
        FacilityEmployee::create([
            'facility_id' => 2,
            'facility_employee_position_id' => 2,
            'name' => 'أحمد الغريبي',
            'national_id' => '1001111116'
        ]);
        FacilityEmployee::create([
            'facility_id' => 2,
            'facility_employee_position_id' => 3,
            'name' => 'كمال حسين',
            'national_id' => '1001111117'
        ]);
        FacilityEmployee::create([
            'facility_id' => 2,
            'facility_employee_position_id' => 4,
            'name' => 'راكان المقاطي',
            'national_id' => '1001111118'
        ]);
        FacilityEmployee::create([
            'facility_id' => 3,
            'facility_employee_position_id' => 1,
            'name' => 'عمار العتيبي',
            'national_id' => '1001111119'
        ]);
        FacilityEmployee::create([
            'facility_id' => 3,
            'facility_employee_position_id' => 2,
            'name' => 'حامد احمد',
            'national_id' => '1001111121'
        ]);
        FacilityEmployee::create([
            'facility_id' => 3,
            'facility_employee_position_id' => 3,
            'name' => 'عماد احمد',
            'national_id' => '1001111122'
        ]);
        FacilityEmployee::create([
            'facility_id' => 3,
            'facility_employee_position_id' => 4,
            'name' => 'ايمن الحربي',
            'national_id' => '1001111123'
        ]);

        // for ($i = 0; $i < 300; $i++) {
        //     FacilityEmployee::create([
        //         'facility_id' => rand(1, 5),
        //         'facility_employee_position_id' => rand(1, 6),
        //         'name' => 'موظف رقم - ' . ($i + 1),
        //         'national_id' => (rand(1000000000, 9999999999)),
        //     ]);
        // }
    }
}
