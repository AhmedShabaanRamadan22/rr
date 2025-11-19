<?php

use Carbon\Carbon;
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
        Schema::table('attachment_labels', function (Blueprint $table) {
            
            DB::table('attachment_labels')->insert([
                [
                    'id' => 48,
                    'label' => "candidate_education_certificate",
                    'placeholder_ar' => "نسخة من شهادة لآخر مؤهل دراسي",
                    'placeholder_en' => "Education certificate",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg', 'pdf']),
                    'is_required' => "1",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 49,
                    'label' => "candidate_course_certificate",
                    'placeholder_ar' => "نسخة من شھادة الدورات التدریبة",
                    'placeholder_en' => "Course certificate",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "0",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 50,
                    'label' => "candidate_experience_certificate",
                    'placeholder_ar' => "نسخة من شھادة الخبرات السابقة",
                    'placeholder_en' => "Experience certificate",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "0",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 51,
                    'label' => "candidate_cv_en",
                    'placeholder_ar' => "(السیرة الذاتیة (محدثة باللغة الانجلیزیة",
                    'placeholder_en' => "English CV",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "1",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 52,
                    'label' => "candidate_passport",
                    'placeholder_ar' => "صورة الجواز( إلزامية للموظفين المقيمين )",
                    'placeholder_en' => "Passport",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "0",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 53,
                    'label' => "candidate_driving_license",
                    'placeholder_ar' => "صورة رخصة القیادة ( ليست إلزامية )",
                    'placeholder_en' => "Driving license",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "0",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => 54,
                    'label' => "candidate_national_address",
                    'placeholder_ar' => "نسخة من العنوان الوطني",
                    'placeholder_en' => "National address photo",
                    'type' => "candidates",
                    'extensions' => json_encode(['png', 'jpg', 'jpeg','pdf']),
                    'is_required' => "1",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachment_labels', function (Blueprint $table) {
            //
        });
    }
};
