<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            //
            DB::table('statuses')->where('id', Status::NEW_TICKET)->update(['description' => (string) 'بلاغ جديد']);
            DB::table('statuses')->where('id', Status::PROCESSING_TICKET)->update(['description' => (string) 'تم استلام البلاغ من غرفة العمليات وتتم الآن مراجعته']);
            DB::table('statuses')->where('id', Status::IN_PROGRESS_TICKET)->update(['description' => (string) 'تتم الآن معالجة البلاغ']);
            DB::table('statuses')->where('id', Status::CLOSED_TICKET)->update(['description' => (string) 'تم إغلاق البلاغ بنجاح']);
            DB::table('statuses')->where('id', Status::NEW_SUPPORT)->update(['description' => (string) 'إسناد جديد']);
            DB::table('statuses')->where('id', Status::PROCESSING_SUPPORT)->update(['description' => (string) 'تم استلام طلب الإسناد من قبل غرفة العمليات والآن قيد المراجعة']);
            DB::table('statuses')->where('id', Status::IN_PROGRESS_SUPPORT)->update(['description' => (string) 'طلب الإسناد قيد التنفيذ']);
            DB::table('statuses')->where('id', Status::CLOSED_SUPPORT)->update(['description' => (string) 'تم إسناد جميع الوجبات وإغلاق الإسناد بنجاح']);
            DB::table('statuses')->where('id', Status::CANCELED_SUPPORT)->update(['description' => (string) 'تم إلغاء طلب الإسناد من قبل المراقب']);
            DB::table('statuses')->where('id', Status::HAS_ENOUGH_SUPPORT)->update(['description' => (string) 'تم الاكتفاء بالوجبات التي أسندت حتى الآن والمركز لا يحتاج الوجبات المتبقية']);
            DB::table('statuses')->where('id', Status::IN_PROGRESS_ASSIST)->update(['description' => (string) 'الدعم الآن قيد التنفيذ']);
            DB::table('statuses')->where('id', Status::DELIVERED_ASSIST)->update(['description' => (string) 'تم توصيل الدعم بنجاح']);
            DB::table('statuses')->where('id', Status::CANCELED_ASSIST)->update(['description' => (string) 'تم إلغاء الدعم']);
            DB::table('statuses')->where('id', Status::CLOSED_MEAL)->update(['description' => (string) 'الوجبة مغلقة']);
            DB::table('statuses')->where('id', Status::OPENED_MEAL)->update(['description' => (string) 'الوجبة مفتوحة وقابلة للتقييم']);
            DB::table('statuses')->where('id', Status::DONE_MEAL)->update(['description' => (string) 'تم الانتهاء من الوجبة بنجاح وتقييم جميع مراحلها']);
            DB::table('statuses')->where('id', Status::CLOSED_MEAL_STAGE)->update(['description' => (string) 'مرحلة الوجبة مغلقة']);
            DB::table('statuses')->where('id', Status::OPENED_MEAL_STAGE)->update(['description' => (string) 'مرحلة الوجبة مفتوحة الآن وقابلة للتقييم']);
            DB::table('statuses')->where('id', Status::DONE_MEAL_STAGE)->update(['description' => (string) 'تم الانتهاء من تقييم مرحلة الوجبة']);
            DB::table('statuses')->where('id', Status::NEW_FINE)->update(['description' => (string) 'المخالفة جديدة']);
            DB::table('statuses')->where('id', Status::ACCEPTED_FINE)->update(['description' => (string) 'المخالفة مقبولة']);
            DB::table('statuses')->where('id', Status::REJECTED_FINE)->update(['description' => (string) 'المخالفة مرفوضة']);
            DB::table('statuses')->where('id', Status::FALSE_TICKET)->update(['description' => (string) 'تم إغلاق البلاغ بسبب كونه بلاغ خاطئ من قبل المراقب']);
            DB::table('statuses')->where('id', Status::CLOSED_MEAL_FOR_SUPPORT)->update(['description' => (string) 'تم إغلاق الوجبة وطلب الإسناد بسبب عدم قدرة المتعهد على إكمال مراحل الطبخ']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            //
        });
    }
};