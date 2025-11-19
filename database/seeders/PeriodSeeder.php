<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::create(['name' => 'الفترة الصباحية',
        //'time' => now(),
        'operation_type_id'=> 3]);
        Period::create(['name' => 'الفترة المسائية',
         //'time' => now(),
          'operation_type_id'=> 3]);
        Period::create(['name' => 'فطور',
         //'time' => now(),
          'operation_type_id'=> 2]);
        Period::create(['name' => 'غداء',
        // 'time' => now(),
          'operation_type_id'=> 2]);
        Period::create(['name' => 'عشاء',
         //'time' => now(),
         'operation_type_id'=> 2]);
        Period::create(['name' => 'فطور',
         'duration' => 4,
         'operation_type_id'=> 5]);
        Period::create(['name' => 'غداء',
         'duration' => 3,
         'operation_type_id'=> 5]);
        Period::create(['name' => 'عشاء',
         'duration' => 5,
         'operation_type_id'=> 5]);


    }
}
