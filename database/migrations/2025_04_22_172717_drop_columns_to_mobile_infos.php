<?php

use App\Models\AttachmentLabel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mobile_infos', function (Blueprint $table) {
            $table->dropColumn('download_android_url');
            $table->dropColumn('download_ios_url');
        });

        AttachmentLabel::create([
            'label' => 'android_app_bundle',
            'placeholder_ar' => 'ملف تطبيق اندرويد',
            'placeholder_en' => 'android app bundle',
            'type' => 'mobile info',
            'is_required' => '1',
            'extensions'  => ['zip'],
        ]);

        AttachmentLabel::create([
            'label' => 'ios_app_bundle',
            'placeholder_ar' => 'ملف تطبيق ابل',
            'placeholder_en' => 'ios app bundle',
            'type' => 'mobile info',
            'is_required' => '1',
            'extensions'  => ['zip'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_infos', function (Blueprint $table) {
            $table->string('download_android_url')->after('current_version');
            $table->string('download_ios_url')->after('download_android_url');
        });
    }
};
