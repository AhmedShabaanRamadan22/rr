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
        Schema::table('attachment_labels', function (Blueprint $table) {
            DB::table('attachment_labels')->insert(
                [
                    'id' => '47',
                    'label' => "sector_sight",
                    'placeholder_ar' => "صورة الشاخص",
                    'placeholder_en' => "Sight photo",
                    'type' => "sectors",
                    'extensions' =>  json_encode(['png', 'jpg', 'jpeg']),
                    'is_required' => "0",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
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
