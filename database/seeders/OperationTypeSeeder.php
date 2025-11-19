<?php

namespace Database\Seeders;

use App\Models\OperationType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OperationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OperationType::create([//1
            'name_en'=> "Raise Ticket",
            'name_ar'=> "رفع بلاغ",
            'description_en'=> "In case of any emergency or issue at the center, you can raise ticket from here",
            'description_ar'=> "في حالة وجود أي حدث طارئ أو مشكلة في المركز، يمكنك رفع البلاغ من هنا",
            "model"=> "tickets",
        ]);

        OperationType::create([//2
            'name_en'=> "Request Food Support",
            'name_ar'=> "طلب اسناد الوجبات",
            'description_en'=> "In case of a shortage of meals, you can request support meals from here",
            'description_ar'=> "في حالة وجود نقص في الوجبات، يمكنك طلب الوجبات الإسنادية من هنا",
            // "quantities"=> [500, 750, 1000, 1250, 1500, 1750, 'all'],
            'model' => 'supports'
        ]);

        OperationType::create([//3
            'name_en'=> "Request Water Support",
            'name_ar'=> "طلب اسناد المياه",
            'description_en'=> "In case of a shortage of drinking water bottles, you can request water support from here",
            'description_ar'=> "في حالة وجود نقص لعبوات مياه الشرب، يمكنك طلب إسناد من هنا",
            // "quantities"=> [83, 125, 166, 208, 250, 291, 'all'],
            'model'=> 'supports'
        ]);

        OperationType::create([//4
            'name_en'=> "Issue Fine",
            'name_ar'=> "تحرير مخالفة",
            'description_en'=> "Issue fines for sctors",
            'description_ar'=> "تحرير مخالفة من قبل المراقب لطلب قطاع",
            // "quantities"=> [83, 125, 166, 208, 250, 291, 'all'],
            'model'=> 'fines'
        ]);

        OperationType::create([//5
            'name_en'=> "Meal stages",
            'name_ar'=> "مراحل الوجبات",
            'description_en'=> "Follow up with meals stages",
            'description_ar'=> "متابعة مراحل الوجبات",
            // "quantities"=> [83, 125, 166, 208, 250, 291, 'all'],
            'model'=> 'meals'
        ]);

    }
}
