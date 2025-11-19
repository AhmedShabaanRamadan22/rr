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
        Schema::table('order_sectors', function (Blueprint $table){
            $table->foreignId('parent_id')->nullable()->references('id')->on('order_sectors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('order_sectors', function (Blueprint $table){
        //     $table->foreignId('parent_id')->constrained()->nullable();
        // });
    }
};
