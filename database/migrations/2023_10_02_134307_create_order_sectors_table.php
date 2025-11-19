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
        Schema::create('order_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
            ->constrained();
            $table->foreignId('sector_id')
            ->constrained();
            // $table->foreignId('parent_id')->constrained()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sectors');
    }
};
