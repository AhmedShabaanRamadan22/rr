<?php

namespace Database\Seeders;

use App\Models\Assist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assist::create([
            'quantity' => 100,
            'support_id' => 2,
            'assigner_id' => 2,
            'assist_sector_id'=>0, 
            'assistant_id' => 3,
            'status_id' => 18,
        ]);
        Assist::create([
            'quantity' => 100,
            'support_id' => 5,
            'assigner_id' => 1,
            'assist_sector_id' => 0,
            'assistant_id' => 6,
            'status_id' => 19,

        ]);
        Assist::create([
            'quantity' => 100,
            'support_id' => 2,
            'assigner_id' => 1,
            'assist_sector_id' => 2,
            'assistant_id' => 5,
            'status_id' => 20,

        ]);
        Assist::create([
            'quantity' => 100,
            'support_id' => 3,
            'assigner_id' => 1,
            'assist_sector_id' => 0,
            'assistant_id' => 10,
            'status_id' => 18,

        ]);
        Assist::create([
            'quantity' => 100,
            'support_id' => 4,
            'assigner_id' => 1,
            'assist_sector_id' => 1,
            'assistant_id' => 5,
            'status_id' => 19,

        ]);
        Assist::create([
            'quantity' => 100,
            'support_id' => 5,
            'assigner_id' => 1,
            'assist_sector_id' => 5,
            'assistant_id' => 5,
            'status_id' => 20,

        ]);
    }
}
