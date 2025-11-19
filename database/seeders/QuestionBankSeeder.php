<?php

namespace Database\Seeders;

use App\Models\QuestionBank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuestionBank::create([//1
            'question_type_id' => 1,
            'regex_id' => 2,
            'content' => 'الاسم:',
        ]);

        QuestionBank::create([//2
            'question_type_id' => 2,
            'regex_id' => 1,
            'content' => 'رقم الهاتف:',
            'placeholder' => '05XXXXXXXX',
        ]);

        QuestionBank::create([//3
            'question_type_id' => 4,
            'regex_id' => 2,
            'content' => 'الايميل:',
            'placeholder' => 'user@user.com',
        ]);

        QuestionBank::create([//4
            'question_type_id' => 8,
            'regex_id' => 2,
            'content' => 'الجنسية:',
        ]);

        QuestionBank::create([//5
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'اطلعني على اخر التحديثات عبر الواتس اب؟',
        ]);
        QuestionBank::create([//6
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل تحتاج إلى تركيب طاولات عمل إضافية في المطبخ؟',
        ]);
        QuestionBank::create([//7
            'question_type_id' => 6,
            'regex_id' => 2,
            'content' => 'نظام التهوية الحالي؟',
        ]);
        QuestionBank::create([//8
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل توجد مياه ساخنة متاحة في المطبخ؟',
        ]);
        QuestionBank::create([//9
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'أي احتياجات خاصة أو ملاحظات إضافية تود ذكرها؟',
        ]);
        QuestionBank::create([//10
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل يوجد نظام إنذار حريق في المطبخ؟',
        ]);
        QuestionBank::create([//11
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل يتوفر في المطبخ (صابون سائل - معقم سائل لأيدي العاملين - مناشف ورقية)؟',
        ]);
        QuestionBank::create([//12
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'المظهر العام للمطبخ نظيف ومناسب لعمليات الطهي والإعداد والتجهيز؟',
        ]);
        QuestionBank::create([//13
            'question_type_id' => 16,
            'regex_id' => 2,
            'content' => 'ارفاق صور البنود العامة:',
        ]);
        QuestionBank::create([//14
            'question_type_id' => 5,
            'regex_id' => 2,
            'content' => 'ارفاق صور بنود التشغيل:',
        ]);
        QuestionBank::create([//15
            'question_type_id' => 5,
            'regex_id' => 2,
            'content' => 'ارفاق صور بنود النقل والتوزيع:',
        ]);
        QuestionBank::create([//16
            'question_type_id' => 5,
            'regex_id' => 2,
            'content' => 'ارفاق صور بنود العاملين:',
        ]);
        QuestionBank::create([//17
            'question_type_id' => 16,
            'regex_id' => 2,
            'content' => 'ارفاق صور بنود التخزين:',
        ]);
        QuestionBank::create([//18
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل يتم حفظ وتخزين المواد الغذائية بطريقة آمنة وسليمة؟',
        ]);
        QuestionBank::create([//19
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => ' هل يوجد شكاوى من (الحجاج / المسؤولين) عن سلامة الوجبات؟',
        ]);
        QuestionBank::create([//20
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => ' هل هناك تدني في مستوى النظافة الشخصية للعاملين؟',
        ]);
        QuestionBank::create([//21
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'شهادات صحية سارية المفعول، للعاملني في المشاعر وتوفري صورة منها؟',
        ]);
        QuestionBank::create([//22
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'هل تم التقيّد بالوقت المناسب للبدء في عمليات الطهي؟',
        ]);
        QuestionBank::create([//23
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'اتباع وتخزين الوجبات في (حافظات خاصة) لنقل الوجبات ولا تستخدم الكراتين الورقية؟',
        ]);
        QuestionBank::create([//24
            'question_type_id' => 10,
            'regex_id' => 2,
            'content' => 'أثناء التوزيع في المخيمات، درجة حرارة الوجبات أعلى من 50 °م؟',
        ]);
        QuestionBank::create([//25
            'question_type_id' => 3,
            'regex_id' => 2,
            'content' => 'هل توجد ملاحظات تود تدوينها',
        ]);
        
    }
}