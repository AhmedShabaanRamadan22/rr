<?php

namespace Database\Seeders;

use App\Models\Support;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Support::create([
            'type'=>2,
            'quantity'=>200,
            'order_sector_id'=>1, //org = 1
            'reason_danger_id'=>4,
            'user_id'=>1,
            'status_id'=>12,
            'period_id'=>3,
            // 'has_enough'=>

        ]);
        Support::create([
            'type'=>3,
            'quantity'=>500,
            'order_sector_id'=>1, 
            'reason_danger_id'=>19,
            'user_id'=>2,
            'status_id'=>13,
            'period_id'=>2,
            // 'has_enough'=>500

        ]);
        
        Support::create([
            'type'=>3, //water
           'quantity'=>550,
            'order_sector_id'=>2, //org = 1
            'reason_danger_id'=>5,
            'user_id'=>3,
            'status_id'=>14,
            'period_id'=>1,
            // 'has_enough'=>200

        ]);
        Support::create([
            'type'=>2,
            'quantity'=>50,
            'order_sector_id'=>3, //org = 1
            'reason_danger_id'=>7,
            'user_id'=>1,
            'status_id'=>12,
            'period_id'=>4,
            // 'has_enough'=>300

        ]);
        Support::create([
            'type'=>3,
            'quantity'=>100,
            'order_sector_id'=>4, //org = 2
            'reason_danger_id'=>20,
            'user_id'=>3,
            'status_id'=>15,
            'period_id'=>2,
            // 'has_enough'=>500

        ]);
    }
}