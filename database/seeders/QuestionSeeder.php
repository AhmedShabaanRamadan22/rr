<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Question::create([
        //     'is_required'=> '1',
        //     'arrangement'=> '1',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '3',
        //     'questionable_type' => 'App\Models\OrganizationService',
        //     'question_bank_organization_id' => '1',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '1',
        //     'is_visible'=> '0',
        //     'questionable_id'=> '2',
        //     'questionable_type' => 'App\Models\OrganizationService',
        //     'question_bank_organization_id' => '2',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '1',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '1',
        //     'questionable_type' => 'App\Models\OrganizationService',
        //     'question_bank_organization_id' => '1',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '1',
        //     'is_visible'=> '0',
        //     'questionable_id'=> '3',
        //     'questionable_type' => 'App\Models\OrganizationService',
        //     'question_bank_organization_id' => '2',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '1',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '6',
        //     'questionable_type' => 'App\Models\OrganizationService',
        //     'question_bank_organization_id' => '4',
        // ]);
        // Question::create([
        //     'is_required'=> '1',
        //     'arrangement'=> '1',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '8',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '1',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '2',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '8',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '2',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '3',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '2',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '4',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '4',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '5',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '3',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '1',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '5',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '2',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '2',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '9',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '1',
        // ]);
        // Question::create([
        //     'is_required'=> '0',
        //     'arrangement'=> '3',
        //     'is_visible'=> '1',
        //     'questionable_id'=> '9',
        //     'questionable_type' => 'App\Models\Section',
        //     'question_bank_organization_id' => '2',
        // ]);
        

        $questions = [
            // [1, '1', '1', 3, 'App\\Models\\OrganizationService', 1],//1
            // [1, '0', '0', 2, 'App\\Models\\OrganizationService', 2],//2
            // [1, '1', '0', 1, 'App\\Models\\OrganizationService', 1],//3
            // [1, '0', '0', 3, 'App\\Models\\OrganizationService', 2],//4
            // [1, '1', '0', 6, 'App\\Models\\OrganizationService', 4],//5

            [1, '1', '1', 1, 'App\\Models\\Section', 1],//6//1
            [2, '1', '0', 1, 'App\\Models\\Section', 2],//7//2
            // [3, '1', '0', 2, 'App\\Models\\Section', 4],//8
            // [4, '1', '0', 5, 'App\\Models\\Section', 3],//9
            // [1, '1', '0', 5, 'App\\Models\\Section', 2],//10
            [2, '1', '0', 2, 'App\\Models\\Section', 1],//11//3
            [3, '1', '0', 2, 'App\\Models\\Section', 2],//12//4
            [1, '1', '1', 5, 'App\\Models\\Section', 6],//13//5
            [2, '1', '1', 5, 'App\\Models\\Section', 7],//14//6
            [3, '1', '1', 5, 'App\\Models\\Section', 8],//15//7
            [1, '1', '1', 7, 'App\\Models\\Section', 13],//16//8
            [2, '1', '1', 7, 'App\\Models\\Section', 5],//17//9
            [1, '0', '1', 6, 'App\\Models\\Section', 9],//18//10
            [2, '1', '1', 6, 'App\\Models\\Section', 12],//19//11
            [3, 'default', '1', 6, 'App\\Models\\Section', 10],//20//12
            // [2, '0', '1', 1, 'App\\Models\\Section', 10],//21
            [2, '1', 'default', 3, 'App\\Models\\Section', 13],//22//13
            [1, '1', '1', 4, 'App\\Models\\Section', 11],//23//14
            [2, '1', '1', 4, 'App\\Models\\Section', 12],//24//15
            [1, '1', '1', 8, 'App\\Models\\Section', 15],//25//16
            [2, '1', '1', 8, 'App\\Models\\Section', 16],//26//17
            [3, '1', '1', 8, 'App\\Models\\Section', 17],//27//18
            [1, '1', '1', 9, 'App\\Models\\Section', 19],//28//19
            [2, 'default', 'default', 9, 'App\\Models\\Section', 20],//29//20
            [3, '1', '1', 9, 'App\\Models\\Section', 18],//30//21
            [4, '1', '1', 3, 'App\\Models\\Section', 21],//31//22
            [3, '1', '1', 4, 'App\\Models\\Section', 22],//32//23
            [3, '1', '1', 3, 'App\\Models\\Section', 23],//33//24
            [1, '1', '1', 3, 'App\\Models\\Section', 6],//34//25
        ];

        foreach ($questions as $question) {
            Question::create([
                'arrangement' => $question[0],
                'is_visible' => $question[1],
                'is_required' => $question[2],
                'questionable_id' => $question[3],
                'questionable_type' => $question[4],
                'question_bank_organization_id' => $question[5],
            ]);
        }

        // Option::create(['content' =>'الاختيار الاول','question_id'=> 1]);
        // Option::create(['content' =>'الاختيار الثاني','question_id'=> 1]);
        // Option::create(['content' =>'الاختيار الثالث', 'question_id'=> 1]);
        Option::create(['content' =>'التكييف المركزي', 'question_id'=> 8]);
        Option::create(['content' =>'التهوية الطبيعية', 'question_id'=> 8]);
        Option::create(['content' =>'التهوية بالضغط الموجب', 'question_id'=> 8]);
        Option::create(['content' =>'التهوية بالضغط السلبي', 'question_id'=> 8]);
        Option::create(['content' =>'التكييف المركزي', 'question_id'=> 25]);
        Option::create(['content' =>'التهوية الطبيعية', 'question_id'=> 25]);
        Option::create(['content' =>'التهوية بالضغط الموجب', 'question_id'=> 25]);
        Option::create(['content' =>'التهوية بالضغط السلبي', 'question_id'=> 25]);

    }
}
