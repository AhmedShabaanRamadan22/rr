<?php

namespace Database\Seeders;

use App\Models\FoodType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodType::create([
            'name' => 'طعام'
        ]);
        FoodType::create([
            'name' => 'شراب'
        ]);
        FoodType::create([
            'name' => 'وجبة خفيفة'
        ]);
        FoodType::create([
            'name' => 'حلا'
        ]);
    }
}
