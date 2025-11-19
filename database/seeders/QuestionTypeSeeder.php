<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // QuestionType::create(['name' => 'text','has_option'=>'0']);//1
        // QuestionType::create(['name' => 'number','has_option'=>'0']);//2
        // QuestionType::create(['name' => 'textarea','has_option'=>'0']);//3
        // QuestionType::create(['name' => 'email','has_option'=>'0']);//4
        // QuestionType::create(['name' => 'file','has_option'=>'0']);//5
        // QuestionType::create(['name' => 'checkbox','has_option'=>'1']);//6
        // QuestionType::create(['name' => 'radio','has_option'=>'1']);//7
        // QuestionType::create(['name' => 'select','has_option'=>'1']);//8
        // QuestionType::create(['name' => 'multiple_select','has_option'=>'1']);//9
        // QuestionType::create(['name' => 'Yes_No','has_option'=>'0']);//10
        // QuestionType::create(['name' => "Apply_Doesn't apply",'has_option'=>'0']);//11
        // QuestionType::create(['name' => "Agree_Doesn't agree",'has_option'=>'0']);//12
        // QuestionType::create(['name' => "Match_Doesn't match",'has_option'=>'0']);//13
        // QuestionType::create(['name' => 'nationalities','has_option'=>'0']);//14
        // QuestionType::create(['name' => 'cities','has_option'=>'0']);//15
        // QuestionType::create(['name' => 'files','has_option'=>'0']);//16

        $questionTypes = [
            ['name' => 'text', 'has_option' => '0'],
            ['name' => 'number', 'has_option' => '0'],
            ['name' => 'textarea', 'has_option' => '0'],
            ['name' => 'email', 'has_option' => '0'],
            ['name' => 'file', 'has_option' => '0'],
            ['name' => 'checkbox', 'has_option' => '1'],
            ['name' => 'radio', 'has_option' => '1'],
            ['name' => 'select', 'has_option' => '1'],
            ['name' => 'multiple_select', 'has_option' => '1'],
            ['name' => 'Yes_No', 'has_option' => '0'],
            ['name' => "Apply_Doesn't apply", 'has_option' => '0'],
            ['name' => "Agree_Doesn't agree", 'has_option' => '0'],
            ['name' => "Match_Doesn't match", 'has_option' => '0'],
            ['name' => 'nationalities', 'has_option' => '0'],
            ['name' => 'cities', 'has_option' => '0'],
            ['name' => 'files', 'has_option' => '0'],
            ['name' => 'rate', 'has_option' => '0'],
            ['name' => 'signature', 'has_option' => '0'],
        ];

        // Loop over the array and create records
        foreach ($questionTypes as $questionType) {
            QuestionType::create($questionType);
        }

    }
}
