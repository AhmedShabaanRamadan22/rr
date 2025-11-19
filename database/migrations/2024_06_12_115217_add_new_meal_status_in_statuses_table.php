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
        Schema::table('statuses', function (Blueprint $table) {
            //
            DB::table('statuses')->insert(
                array(
                    'id' => '42',
                    'name_ar' => "مغلق بسبب عدم القدرة على اكمال مراحل الطبخ",
                    'name_en' => "Closed due to inability of completing meal stages",
                    'color' => "#FFD573",
                    'type' => "meals",
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