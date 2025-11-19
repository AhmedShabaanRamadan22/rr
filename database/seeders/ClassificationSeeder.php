<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classification::create([//1
            'code'=>'SS',
            'guest_value'=>20000,
            'organization_id'=>1,
            'description'=>'special high class'
        ]);
        Classification::create([//2
            'code'=>'S',
            'guest_value'=>10000,
            'organization_id'=>1,
            'description'=>'super high class'

        ]);
        Classification::create([//3
            'code'=>'A',
            'guest_value'=>5000,
            'organization_id'=>1,
            'description'=>'high class'

        ]);
        Classification::create([//4
            'code'=>'B',
            'guest_value'=>1000,
            'organization_id'=>1,
            'description'=>'good class'

        ]);
        Classification::create([//5
            'code'=>'A',
            'guest_value'=>3000,
            'organization_id'=>2,
            'description'=>'high class'

        ]);
        Classification::create([//6
            'code'=>'B',
            'guest_value'=>2000,
            'organization_id'=>2,
            'description'=>'good class'

        ]);

    }
}
