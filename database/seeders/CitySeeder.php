<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Log;


class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Path to your CSV file
        // $path = base_path() . '/database/data/sql/saudi_cities.csv';

        // // // Read the CSV file
        // // $csvData = File::get($path);
        // // $rows = array_map("str_getcsv", explode("\n", $csvData));

        // // Define your table name
        // $tableName = 'saudi_cities';

        // // Remove the header row if it exists
        // $header = array_shift($rows);

        

        // // Insert data into the database
        // foreach ($rows as $row) {
        //     // Check if the number of columns matches the number of headers
        //     if (count($header) !== count($row)) {
        //         // Log an error or handle it based on your application's needs
        //         \Log::error("Row skipped. Number of columns does not match number of headers.");
        //         continue;
        //     }
        //     // Generate unique ID
        //     $data = ['id' => $idCounter++] + array_combine($header, $row);

        //     // Insert data into the database
        //     DB::table($tableName)->insert($data);
        // }

        $path = base_path() . '/database/data/sql/saudi_cities.sql';
        DB::unprepared(file_get_contents($path));
        // $path = base_path() . '/database/data/sql/saudi_cities.sql';
        // DB::unprepared(file_get_contents($path));
    }
}
