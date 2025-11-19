<?php

namespace Database\Seeders;

use App\Models\Danger;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Danger::create([//1
            'level' => 'مرتفع',
            'color' => '#EE6363'
        ]);

        Danger::create([//2
            'level' => 'متوسط',
            'color' => '#F0A44B'
        ]);

        Danger::create([//3
            'level' => 'منخفض',
            'color' => '#F0C24B'
        ]);
        
        Danger::create([//4
            'level' => 'لا يوجد مستوى خطورة'
        ]);
    }
}
