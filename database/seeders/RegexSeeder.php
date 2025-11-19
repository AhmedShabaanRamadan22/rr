<?php

namespace Database\Seeders;

use App\Models\Regex;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Regex::create(['name' => 'phone regex', 'description' => '10 digit phone number', 'value' => '']);
        Regex::create(['name' => 'no regex', 'description' => '----', 'value' => '']);

    }
}