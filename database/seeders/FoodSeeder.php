<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Food::create([
            "name" => "معصوب",
            "food_type_id" => 1
        ]);
        Food::create([
            "name" => "رز دجاج",
            "food_type_id" => 1
        ]);
        Food::create([
            "name" => "ايدام كاري",
            "food_type_id" => 1
        ]);
        Food::create([
            "name" => "عصير برتقال",
            "food_type_id" => 2
        ]);
    }
}
