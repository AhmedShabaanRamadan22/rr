<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('statuses', function (Blueprint $table) {

            DB::table('statuses')->insert(
                array(
                    'id' => '41',
                    'name_ar' => "بلاغ خاطئ",
                    'name_en' => "False ticket",
                    'color' => "#9764B9",
                    'type' => "tickets",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                )
            );
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