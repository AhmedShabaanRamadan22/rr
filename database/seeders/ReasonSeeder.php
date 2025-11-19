<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reason::create([//1
            'name' => 'عدم وصول المواد الاولية والاساسية',
        ]);
        
        Reason::create([//2
            'name' => 'سوء عملية تنظيم وجدولة توزيع الوجبات',
        ]);
        
        Reason::create([//3
            'name' => 'عدم وجود حراس أمن داخل المخيمات ',
        ]);

    
        Reason::create([//4
            'name' => 'تقديم وجبات مفتوحة للحجاج ',
        ]);
        
        Reason::create([//5
            'name' => 'عدم توفر حافظات حرارية للغذاء',
        ]);
        
        Reason::create([//6
            'name' => 'عدم وصول الغذاء المساند للوجبة الرئيسية',
        ]);
        
        Reason::create([//7
            'name' => 'تذويب المواد الاولية بطريقة خاطئة',
        ]);

        Reason::create([//8
            'name' => 'عدم توفر كميات كافية من المياه',
        ]);

        Reason::create([//9
            'name' => 'عدم الاستجابة لصيانة الموقد',
        ]);

        Reason::create([//10
            'name' => 'عدم توفر كميات كافية من موارد الطبخ',
        ]);

        Reason::create([//11
            'name' => 'اختلاف ضغط المياه وقوة الدفع',
        ]);

        Reason::create([//12
            'name' => 'عدم توفر كمية كافية من الوجبات',
        ]);

        Reason::create([//13
            'name' => 'اعطال في الاجهزة الرئيسية',
        ]);

        Reason::create([//14
            'name' => 'اعطال في جزء من الاجهزة الرئيسية',
        ]);

        Reason::create([//15
            'name' => 'اعطال في الاضاءة',
        ]);

        Reason::create([//16
            'name' => 'اعطال في التصريف الصحي',
        ]);

        Reason::create([//17
            'name' => 'انقطاع الكهرباء',
        ]);
        

        Reason::create([//18
            'name' => 'اعطال في التهوية',
        ]);

        Reason::create([//19
            'name' => 'نقص مواد تغليف الوجبات الغذائية',
        ]);

        Reason::create([//20
            'name' => 'اعطال في الادوات الصحية',
        ]);

        Reason::create([//21
            'name' => 'نقص مواد السلامة',
        ]);

        Reason::create([//22
            'name' => 'عدم تخزين المواد الاولية والاساسية بشكل صحيح',
        ]);

        Reason::create([//23
            'name' => 'عدم وجود عدد كافي من الهوت كابينت',
        ]);

        Reason::create([//24
            'name' => 'انقطاع احد الموارد الاساسية',
        ]);

        Reason::create([//25
            'name' => 'نقل مياه الشرب في سيارات غير مبردة',
        ]);

        Reason::create([//25
            'name' => 'اذابة المواد الاولية والاساسية بطريقة خاطئة',
        ]);

        Reason::create([//27
            'name' => 'اعطال في غرف التجميد',
        ]);

        Reason::create([//28
            'name' => 'عدم الحفاظ على العينات المرجعية ',
        ]);

        Reason::create([//29
            'name' => 'عدم اجادة التعامل مع ادوات المطبخ',
        ]);

        Reason::create([//30
            'name' => 'عدم توصيل الكهرباء للمناطق بشكل كاف',
        ]);

        Reason::create([//31
            'name' => 'اغلاق اجهزة المطبخ بشكل عشوائي',
        ]);

        Reason::create([//32
            'name' => 'عدم توفر اضاءة كافية',
        ]);

        Reason::create([//33
            'name' => 'عدم فصل المواد في غرف التبريد',
        ]);
        
        Reason::create([//34
            'name' => 'عدم نظافة ادوات المطبخ',
        ]);

        Reason::create([//35
            'name' => 'انقطاع في المياه',
        ]);

        Reason::create([//36
            'name' => 'عدم الالتزام بمدة صلاحية الغذاء',
        ]);

        Reason::create([//37
            'name' => 'عدم توفر مساحات كافية',
        ]);

        Reason::create([//38
            'name' => 'عدم ملائمة درجات حرارة غرف التبريد للمواد المخزنة',
        ]);

        Reason::create([//39
            'name' => 'تعطل سير عملية الطبخ',
        ]);

        Reason::create([//40
            'name' => 'عدم اكتمال عملية الطبخ',
        ]);

        Reason::create([//41
            'name' => 'عدم توفر كميات كافية من حافظات الطعام ',
        ]);

        Reason::create([//42
            'name' => 'انسكاب الماء على مجرى مولدات الغاز ',
        ]);

        Reason::create([//43
            'name' => 'عدم استجابة العاملين للتعليمات',
        ]);

        Reason::create([//44
            'name' => 'عدم نضج الغذاء بشكل جيد ',
        ]);

        Reason::create([//45
            'name' => 'عدم استجابة المتعهد',
        ]);

        Reason::create([//46
            'name' => 'عدم توفر سكن للمراقبين خلال فترة الحج ',
        ]);





    }
}