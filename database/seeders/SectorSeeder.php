<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sector::create([//1
            'label'=>'1-1-B',
            'sight'=>'A1',
            'guest_quantity'=>50,
            'classification_id'=>1,//org = 1
            'nationality_organization_id'=>1,
            'longitude' => '39.8589',
            'latitude' => '21.3898', // Adjusted to 21.4457
            'manager_id'=>1,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
        Sector::create([//2
            'label'=>'777',
            'sight'=>'B1',
            'guest_quantity'=>30,
            'classification_id'=>2,//org = 1
            'nationality_organization_id'=>2,
            'longitude' => '39.8599',
            'latitude' => '21.3905', // Adjusted to 21.4622
            'manager_id'=>3,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
        Sector::create([//3
            'label'=>'12',
            'sight'=>'A2',
            'guest_quantity'=>50,
            'classification_id'=>3,//org = 1
            'nationality_organization_id'=>1,
            'longitude' => '39.8609',
            'latitude' => '21.3912', // Adjusted to 21.4230
            'manager_id'=>2,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
        Sector::create([//4
            'label'=>'Af11',
            'sight'=>'A2',
            'guest_quantity'=>50,
            'classification_id'=>5,//org = 2
            'nationality_organization_id'=>3,
            'longitude' => '39.8579', 
            'latitude' => '21.3891', // Adjusted to 21.4351,
            'manager_id'=>4,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
        Sector::create([//5
            'label'=>'Af12',
            'sight'=>'A2',
            'guest_quantity'=>150,
            'classification_id'=>6,//org = 2
            'nationality_organization_id'=>3,
            'longitude' => '39.8589',
            'latitude' => '21.3898', // Adjusted to 21.4457
            'manager_id'=>1,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
        Sector::create([//6
            'label'=>'Af13',
            'sight'=>'A2',
            'guest_quantity'=>150,
            'classification_id'=>6,//org = 2
            'nationality_organization_id'=>4,
            'longitude' => '39.8649',
            'latitude' => '21.3940', // Adjusted to 21.4371
            'manager_id'=>1,
            'supervisor_id'=>9,
            'boss_id'=>8,
        ]);
    }
}
