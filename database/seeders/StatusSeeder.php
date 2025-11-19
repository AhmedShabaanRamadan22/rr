<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $statuses = [
            ['1',"جديد","New","#837f7e",'orders'], //1
            ['2',"قيد المراجعة","Processing","#399ddd",'orders'], //2
            // ['3',"تم التأكيد","Confirmed","#81b29a",'orders'], //3
            ['3',"تم التدقيق","Confirmed","#81b29a",'orders'], //3
            ['4',"تم التأهيل الاولي","Approved","#00aabb",'orders'], //4
            // ['5',"تم القبول","Accepted","#65cb66",'orders'], //5
            ['5',"تم القبول النهائي","Accepted","#65cb66",'orders'], //5
            ['6',"عدم التأهيل","Rejected","#e75f47",'orders'], //6
            // ['6',"تم الرفض","Rejected","#e75f47",'orders'], //6
            ['7',"تم الالغاء","Canceled","#e29697",'orders'], //7
            ['8',"جديد","New","#837f7e",'tickets'], //8
            ['9',"قيد المراجعة","Processing", "#00aabb", 'tickets'], //9
            ['10',"قيد التنفيذ","In progress", "#399ddd", 'tickets'], //10
            ['11',"مغلق","Closed", "#e29697", 'tickets'], //11
            ['12',"جديد","New","#837f7e",'supports'], //12
            ['13',"قيد المراجعة","Porcessing", "#00aabb", 'supports'], //13
            ['14',"قيد التنفيذ","In progress", "#399ddd", 'supports'], //14
            ['15',"مغلق","Closed", "#e75f47", 'supports'], //15
            ['16',"تم الالغاء","Canceled", "#e29697", 'supports'], //16
            ['17',"تم الاكتفاء","Has enough", "#81b29a", 'supports'], //17
            ['18',"قيد التنفيذ","In progress", "#399ddd", 'assists'], //18
            ['19',"تم التسليم","Delivered", "#65cb66", 'assists'], //19
            ['20',"تم الالغاء","Canceled", "#e29697", 'assists'], //20
            ['21',"جديد","New","#837f7e",'candidates'], //21
            ['22',"قيد المراجعة","Processing","#399ddd",'candidates'], //22
            ['23',"تم القبول","Accepted","#65cb66",'candidates'], //23
            ['24',"تم الرفض","Rejected","#e75f47",'candidates'], //24
            ['25',"مغلق","Closed", "#e29697", 'meals'], //25
            ['26',"مفتوح","Opened", "#399ddd", 'meals'], //26
            ['27',"تمت الإجابة","Done", "#65cb66", 'meals'], //27
            ['28',"مغلق","Closed", "#e29697", 'meal_stages'], //28
            ['29',"مفتوح","Opened", "#399ddd", 'meal_stages'], //29
            ['30',"تمت الإجابة","Done", "#65cb66", 'meal_stages'], //30
            ['31',"جديد","New","#837f7e", 'fines'], //31
            ['32',"تم القبول","Accepted","#65cb66", 'fines'], //32
            ['33',"تم الرفض","Rejected", "#e29697", 'fines'], //33
            ['34',"جديد","New", "#a4227f", 'order_interviews'], //34
            ['35',"مقبول","Accepted", "#65cb66", 'order_interviews'], //35
            ['36',"مرفوض","Rejected", "#e29697", 'order_interviews'], //36
            ['37',"نصف مقبول","Half_Accepted", "#a4b17f", 'order_interviews'], //37
            ['38',"قبول مبدئي","Approved","#00ff00",'candidates'], //38
            ['39',"بانتظار استكمال البيانات","Awaiting Data Completion","#ffd700",'candidates'], //39
            ['40',"تم استكمال البيانات","Completed Data","#4682b4",'candidates'], //40
            // ['41',"بلاغ خاطئ","False ticket","#9764B9",'tickets'], //41
            // ['42',"مغلق بسبب طلب الإسناد","Closed due to support","#929997",'meals'], //42

        ];
        foreach($statuses as $status){

            Status::create([
                "id" => $status[0],
                "name_ar"  => $status[1],
                "name_en" => $status[2],
                "color"=>$status[3],
                "type"=>$status[4],
            ]);
        }
    }
}