<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Subject::create([
        //     'name_ar'=>'استفسار',
        //     'name_en'=>'inquiry'
        // ]);

        Subject::create([
            'name_ar'=>'سؤال',
            'name_en'=>'question'
        ]);

        Subject::create([
            'name_ar'=>'خدمة',
            'name_en'=>'service'
        ]);

        Subject::create([
            'name_ar'=>'مشكلة تقنية',
            'name_en'=>'tech issue'
        ]);

        Subject::create([
            'name_ar'=>'اخرى',
            'name_en'=>'other'
        ]);
    }
}
