<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable();
        });

        // Update each existing record with a unique UUID
        DB::table('meals')->orderBy('id')->chunk(100, function ($meals) {
            foreach ($meals as $meal) {
                DB::table('meals')->where('id', $meal->id)->update(['uuid' => (string) Str::uuid()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            //
        });
    }
};
