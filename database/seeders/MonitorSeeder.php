<?php

namespace Database\Seeders;

use App\Models\Monitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Monitor::create([
            'user_id'=>2,
            'code'=>'A1',
            // 'bravo_number'=>50,
            // 'has_received'=>1,
            // 'has_returned'=>0

        ]);
        Monitor::create([
            'user_id'=>5,
            'code'=>'A2',
            // 'bravo_number'=>100,
            // 'has_received'=>1,
            // 'has_returned'=>1

        ]);
        Monitor::create([
            'user_id'=>4,
            'code'=>'A3',
            // 'bravo_number'=>120,
            // 'has_received'=>0,
            // 'has_returned'=>0

        ]);
    }
}
