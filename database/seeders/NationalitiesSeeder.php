<?php

namespace Database\Seeders;

use App\Models\Nationality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class NationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Nationality::create(['name'=> 'ماليزيا','flag'=>'my']);
        Nationality::create(['name'=> 'موريتانيا','flag'=>'mr']);
        Nationality::create(['name'=> 'ليبيا','flag'=>'ly']);
        Nationality::create(['name'=>'اندونيسيا','flag'=>'id']);


    }
}