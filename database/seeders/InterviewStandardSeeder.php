<?php

namespace Database\Seeders;

use App\Models\InterviewStandard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterviewStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InterviewStandard::create([
            'name'=>'عدد العمالة',
            'max_score'=>15,
        ]);
        InterviewStandard::create([
            'name'=>'مساحة المطبخ',
            'max_score'=>10,
        ]);
        InterviewStandard::create([
            'name'=>'عدد سنوات السجل التجاري',
            'max_score'=>5,
        ]);
        InterviewStandard::create([
            'name'=>'رأس المال في السجل التجاري',
            'max_score'=>10,
        ]);
        InterviewStandard::create([
            'name'=>'تقييم المنصة لموسم ١٤٤٣',
            'max_score'=>10,
        ]);
        InterviewStandard::create([
            'name'=>'تقييم اللجنة حسب المقابلة الشخصية',
            'max_score'=>25,
        ]);
        InterviewStandard::create([
            'name'=>'تقييم عقد مع شركة صحة وسلامة الغذاء',
            'max_score'=>25,
        ]);
    }
}
