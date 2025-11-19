<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Form::create([//1
        //     'organization_service_id' => 1,
        //     'name' => 'Registration_Form_01',
        //     'code' => 'REG_F01',
        //     'is_visible' => '1',
        //     'display' => 'WEB',
        //     'organization_category_id' => 1,
        // ]);
        // Form::create([//2
        //     'organization_service_id' => 1,
        //     'name' => 'Complaint_Form_02',
        //     'organization_category_id' => 1,
        //     'code' => 'COM_F02',
        //     'display' => 'WEB',
        //     'is_visible' => '1'
        // ]);
        // Form::create([//3
        //     'organization_service_id' => 2,
        //     'name' => 'Registration_Form_03',
        //     'display' => 'WEB',
        //     'code' => 'REG_F03',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        // Form::create([//4
        //     'organization_service_id' => 2,
        //     'name' => 'استمارة اكتمال الموارد_04',
        //     'display' => 'APP',
        //     'code' => 'REG_F04',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        Form::create([//5//1
            'organization_service_id' => 2,
            'name' => 'استمارة تقييم اشتراطات تطوير المطابخ_01',
            'code' => 'REG_F01',
            'is_visible' => '1',
            'organization_category_id' => 2,
        ]);
        // Form::create([//6
        //     'organization_service_id' => 3,
        //     'name' => 'استمارة صيانة سخانات المياه_06',
        //     'display' => 'APP',
        //     'code' => 'REG_F06',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        // Form::create([//7
        //     'organization_service_id' => 3,
        //     'name' => 'استمارة صيانة شهرية_07',
        //     'display' => 'APP',
        //     'code' => 'REG_F07',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        Form::create([//8//2
            'organization_service_id' => 3,
            'name' => 'استمارة جاهزية المرافق_02',
            'display' => 'APP',
            'code' => 'REG_F02',
            'is_visible' => '1',
            'organization_category_id' => 1,
        ]);
        Form::create([//9//3
            'organization_service_id' => 1,
            'name' => 'استمارة تقييم اشتراطات صحة وسلامة الموارد_03',
            'code' => 'REG_F03',
            'is_visible' => '1',
            'organization_category_id' => 1,
        ]);
        // Form::create([//10
        //     'organization_service_id' => 1,
        //     'name' => 'استمارة صحة وسلامة الغذاء_10',
        //     'code' => 'REG_F10',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        // Form::create([//11
        //     'organization_service_id' => 3,
        //     'name' => 'استمارة اثبات صحة وسلامة سخانات الطعام_11',
        //     'code' => 'REG_F11',
        //     'is_visible' => '1',
        //     'organization_category_id' => 2,
        // ]);
        Form::create([//12//4 
            'organization_service_id' => 4,
            'name' => 'استمارة تقييم اشتراطات تطوير المطابخ_04',
            'code' => 'REG_F04',
            'is_visible' => '1',
            'organization_category_id' => 5,
        ]);

    }
}
