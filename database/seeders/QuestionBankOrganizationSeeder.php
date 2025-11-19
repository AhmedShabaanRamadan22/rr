<?php

namespace Database\Seeders;

use App\Models\QuestionBankOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionBankOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // QuestionBankOrganization::create([//1
        //     'organization_id' => 1, 
        //     'question_bank_id' => 1, 
        //     // 'is_visible' => , 
        //     // 'is_required' => ,
        //     'description' => 'الرجاء ادخال الاسم الرباعي'
        // ]);

        // QuestionBankOrganization::create([//2
        //     'organization_id' => 1, 
        //     'question_bank_id' => 5, 
        //     // 'is_visible' => , 
        //     'is_required' => 0,
        //     // 'description' => 'الرجاء ادخال الاسم '
        // ]);

        // QuestionBankOrganization::create([//3
        //     'organization_id' => 2, 
        //     'question_bank_id' => 1, 
        //     // 'is_visible' => , 
        //     // 'is_required' => ,
        //     'description' => 'الرجاء ادخال الاسم الثلاثي'
        // ]);

        // QuestionBankOrganization::create([//4
        //     'organization_id' => 2, 
        //     'question_bank_id' => 3, 
        //     // 'is_visible' => , 
        //     // 'is_required' => ,
        //     'description' => 'الرجاء ادخال ايميل فعال'
        // ]);
        $questions = [
            [1, 1, 1, '1', '1', 'الرجاء ادخال الاسم الرباعي'],
            [2, 5, 1, '1', '', null],
            [3, 1, 2, '1', '1', 'الرجاء ادخال الاسم الثلاثي'],
            [4, 3, 2, '1', '1', 'الرجاء ادخال ايميل فعال'],
            [5, 17, 1, '1', '1', '...'],
            [6, 7, 1, '1', '1', '...'],
            [7, 6, 1, '1', '0', '...'],
            [8, 8, 1, '1', '1', '...'],
            [9, 22, 1, '1', '1', '...'],
            [10, 11, 1, '1', '1', '...'],
            [11, 10, 1, '1', '1', '...'],
            [12, 19, 1, '1', '1', '...'],
            [13, 18, 1, '1', '1', '...'],
            [14, 6, 2, '0', '0', '...'],
            [15, 11, 2, '1', '1', '...'],
            [16, 9, 2, '1', '0', '...'],
            [17, 13, 2, '1', '1', '...'],
            [18, 17, 2, '1', '1', '...'],
            [19, 23, 2, '1', '1', '...'],
            [20, 18, 2, '1', '1', '...'],
            [21, 14, 1, '1', '1', '...'],
            [22, 13, 1, '1', '1', 'يجب ارفاق ما لا يقل عن ٣ مرفقات (صورة -فيديو)'],
            [23, 25, 1, '1', '1', '...'],
        ];

        foreach ($questions as $question) {
            QuestionBankOrganization::create([
                'question_bank_id' => $question[1],
                'organization_id' => $question[2],
                'is_visible' => $question[3],
                'is_required' => $question[4],
                'description' => $question[5]
            ]);
        }
    }
}
