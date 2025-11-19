<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Section::create([//1
        //     'form_id' => 1,
        //     'name' => 'Personal_Information',
        //     'arrangement' => '1',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//2
        //     'form_id' => 1,
        //     'name' => 'downloadAttachment',
        //     'arrangement' => '2',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//3
        //     'form_id' => 2,
        //     'name' => 'Facility_Information',
        //     'arrangement' => '1',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//4
        //     'form_id' => 11,
        //     'name' => 'قسم بنود التشغيل',
        //     'arrangement' => '1',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//5
        //     'form_id' => 11,
        //     'name' => 'قسم البنود العامة',
        //     'arrangement' => '2',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//6
        //     'form_id' => 10,
        //     'name' => 'قسم بنود التشغيل',
        //     'arrangement' => '1',
        //     'is_visible' => '1'
        // ]);
        // Section::create([//7
        //     'form_id' => 10,
        //     'name' => 'قسم البنود العامة',
        //     'arrangement' => '2',
        //     'is_visible' => '1'
        // ]);
        Section::create([//8//1
            'form_id' => 2,//8,
            'name' => 'قسم بنود التشغيل',
            'arrangement' => '2',
            'is_visible' => '1'
        ]);
        Section::create([//9//2
            'form_id' => 2,//8,
            'name' => 'قسم البنود العامة',
            'arrangement' => '1',
            'is_visible' => '1'
        ]);
        Section::create([//10//3
            'form_id' => 3,//9,
            'name' => 'قسم بنود التشغيل',
            'arrangement' => '2',
            'is_visible' => '1'
        ]);
        Section::create([//11//4
            'form_id' => 3,//9,
            'name' => 'قسم البنود العامة',
            'arrangement' => '1',
            'is_visible' => '1'
        ]);
        Section::create([//12//5
            'form_id' => 1,//5,
            'name' => 'قسم بنود التشغيل',
            'arrangement' => '2',
            'is_visible' => '1'
        ]);
        Section::create([//13//6
            'form_id' => 1,//5,
            'name' => 'قسم البنود العامة',
            'arrangement' => '1',
            'is_visible' => '0'
        ]);
        Section::create([//14//7
            'form_id' => 1,//5,
            'name' => 'قسم بنود التخزين',
            'arrangement' => '3',
            'is_visible' => '1'
        ]);
        Section::create([//15//8
            'form_id' => 4,//12,
            'name' => 'قسم البنود العامة',
            'arrangement' => '1',
            'is_visible' => '0'
        ]);
        Section::create([//16//9
            'form_id' => 4,//12,
            'name' => 'قسم بنود التخزين',
            'arrangement' => '2',
            'is_visible' => '1'
        ]);

    }
}
