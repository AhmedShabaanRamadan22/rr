<?php

namespace Database\Seeders;

use App\Models\StageBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StageBank::create([
            'name'=>'جاهزية المطبخ',
            // 'start_at'=>0,
            // 'end_at'=>0,
            'duration'=>30,
            'description'=>'يتم التحقق من جاهزية المطبخ ثم فحص مستلزمات الطهي وضمان توفر جميع المكونات والأدوات اللازمة'
        ]);
        StageBank::create([
            'name'=>'وصول فريق العمل',
            // 'user_id'=>3,
            // 'status_id'=>4,
            // 'period_id'=>3,
            // 'is_pass'=>0,
            'duration'=>20,
            'description'=>'يتم التأكد من استعداد الفريق للعمل والتأكد من ارتداء الفريق للزي المناسب والوقاية الشخصية'
        ]);
        StageBank::create([
            'name'=>'بدء عمليات الطبخ',
            // 'user_id'=>2,
            // 'status_id'=>1,
            // 'period_id'=>3,
            // 'is_pass'=>0,
            'duration'=>15,
            'description'=>'يتم تنفيذ وصفات الطهي والتأكد من اتباع الخطوات بدقة مع مراقبة درجات الحرارة وضبط الأوقات المحددة لضمان جودة الطعام'
        ]);
        StageBank::create([
            'name'=>'التعبئة والتغليف',
            // 'user_id'=>2,
            // 'status_id'=>1,
            // 'period_id'=>3,
            // 'is_pass'=>0,
            'duration'=>60,
            'description'=>'يتم تغليف الوجبات بشكل نظيف وفعّال، مع التأكد من استخدام مواد آمنة وصحية'
        ]);
        StageBank::create([
            'name'=>'توزيع الوجبات',
            // 'start_at'=>0,
            // 'end_at'=>0,
            // 'user_id'=>2,
            // 'status_id'=>1,
            // 'period_id'=>3,
            // 'is_pass'=>0,
            'duration'=>75,
            'description'=>'يتم توجيه الفريق لتوزيع الوجبات حسب الجدول الزمني المحدد ومكان التوصيل'
        ]);
    }
}
