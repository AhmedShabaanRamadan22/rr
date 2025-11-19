<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path() . '/database/data/sql/countries.sql';
        DB::unprepared(file_get_contents($path));
        $path = base_path() . '/database/data/sql/continents.sql';
        DB::unprepared(file_get_contents($path));
    }
}
