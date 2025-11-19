<?php

use Illuminate\Support\Carbon;
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
        Schema::table('question_types', function($table){

            // $table->increments('id');
            // $table->string('email', 255);
            // $table->string('password', 64);
            // $table->timestamps();
    
            DB::table('question_types')->insert(
                array(
                    'name' => "signature",
                    'has_option' => "0",
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
        Schema::table('question_types', function (Blueprint $table) {
            //
        });
    }
};
