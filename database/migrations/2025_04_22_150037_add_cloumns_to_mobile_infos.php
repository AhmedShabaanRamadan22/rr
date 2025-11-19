<?php

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
            $table->string('about_us')->nullable()->change();
            $table->string('term_conditions')->nullable()->change();
            $table->string('download_android_url')->after('current_version');
            $table->string('download_ios_url')->after('download_android_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_infos', function (Blueprint $table) {
            $table->string('about_us')->nullable(false)->change();
            $table->string('term_conditions')->nullable(false)->change();
            $table->dropColumn('download_android_url');
            $table->dropColumn('download_ios_url');
        });
    }
};
